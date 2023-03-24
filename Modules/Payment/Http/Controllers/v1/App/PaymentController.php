<?php

namespace Modules\Payment\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\User\Transformers\UserResource;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Cart\Transformers\App\PaymentResource;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Events\App\OrderCreated;
use Modules\Order\Repository\OrderRepositoryInterface;
use Modules\Payment\Repository\PaymentMethodRepositoryInterface;
use Modules\Payment\Repository\PaymentRepositoryInterface;
use Modules\Payment\Services\PaymentService;
use Modules\Payment\Transformers\App\CartShippingResource;
use Modules\Payment\Transformers\App\Checkout\OrderResource;
use Modules\Payment\Transformers\Panel\PaymentMethodResource;
use Modules\Product\Entities\Product;

class PaymentController extends Controller
{
    private $paymentMethodRepo;
    private $paymentRepo;
    private $orderRepo;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepo,
        PaymentRepositoryInterface $paymentRepo,
        OrderRepositoryInterface $orderRepo
    ) {
        $this->paymentMethodRepo = $paymentMethodRepo;
        $this->paymentRepo = $paymentRepo;
        $this->orderRepo = $orderRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function init()
    {
        $user = auth()->user();

        $cart = $user->cart;

        $cart_shippings = $cart->shippings;

        $payment_methods = $this->paymentMethodRepo->allActive();

        $data = array(
            'cart_shipments' => CartShippingResource::collection($cart_shippings),
            'cart' => new CartResource(Cart::content(true)),
            'user' => new UserResource($user),
            'payment_methods' => PaymentMethodResource::collection($payment_methods),
            'address' => $cart->address
        );

        ApiService::_success($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO:REFACTOR ACTIONS

        $user = auth()->user();

        $cart = $user->cart;

        $cart_content = Cart::content(true);

        $data = [
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'payment_method_id' => $request->payment_method,
            'status' => OrderStatus::WaitPayment,
            'payable_price' => $cart_content->payable_price,
            'remaining_amount' => $cart_content->payable_price,
            'payment_remaining_time' => now()->addHour(),
            'is_returnable' => 0,
            'price' => json_encode([
                'payable_price' => $cart_content->payable_price,
                'rrp_price' => $cart_content->rrp_price,
                'shipment_cost' => $cart_content->shipment_cost
            ]),
        ];

        $order = $this->orderRepo->create($data);

        foreach ($cart->shippings as $key => $shipping) {

            $shipment_date = ($shipping->shipment_interval_id) ? $shipping->interval->shipment_date->date : null;

            $shipment_data = [
                'shipment_id' => $shipping->shipment_id,
                'order_id' => $order->id,
                'date' => $shipment_date,
                'status' => OrderStatus::WaitPayment,
            ];

            if (!is_null($shipping->shipment_interval_id)) {
                $shipment_data['start_date'] = $shipping->interval->start_at;
                $shipment_data['end_date'] = $shipping->interval->end_at;
            }

            $order_shipping = $order->shippings()->create($shipment_data);


            $order_shipping_items = array();

            foreach ($shipping->cart_items as $key => $value) {
                array_push($order_shipping_items, [
                    'product_id' => $value->cart_item->product_id,
                    'variant_id' => $value->cart_item->variant_id,
                    'quantity' => $value->cart_item->quantity,
                    'price' => [
                        'selling_price' => $value->cart_item->variant->price - $value->cart_item->variant->price * ($value->cart_item->variant->calculate_discount / 100),
                        'rrp_price' => $value->cart_item->variant->price,
                        'discount_percent' => $value->cart_item->variant->calculate_discount
                    ],
                ]);
            }
            $order_shipping->items()->createMany($order_shipping_items);
        }

        event(new OrderCreated($order, $order_shipping));


        $callback = PaymentService::generate((int) $cart_content->payable_price / 10, $order, $request->payment_method);

        $callback = json_decode($callback);

        ApiService::_success($callback->action);
    }

    public function callback(Request $request)
    {
        return PaymentService::verify($request);
    }

    public function checkout(Request $request)
    {
        $payment = $this->paymentRepo->findByReferenceCode($request->reference_code);

        $data = array(
            'order' => new OrderResource($payment->paymentable),
            'status' => $payment->status,
            'payment_method' => new PaymentMethodResource($payment->payment_method)
        );
        ApiService::_success($data);
    }
}
