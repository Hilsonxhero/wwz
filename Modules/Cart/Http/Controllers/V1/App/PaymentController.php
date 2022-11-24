<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\User\Transformers\UserResource;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Cart\Transformers\App\PaymentResource;
use Modules\Payment\Repository\PaymentMethodRepositoryInterface;
use Modules\Payment\Transformers\App\CartShippingResource;
use Modules\Payment\Transformers\Panel\PaymentMethodResource;

class PaymentController extends Controller
{
    private $paymentMethodRepo;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepo)
    {
        $this->paymentMethodRepo = $paymentMethodRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function init()
    {
        $user  = auth()->user();

        $cart = $user->cart;



        $cart_shippings = $cart->shippings;

        $payment_methods = $this->paymentMethodRepo->allActive();

        $data = array(
            'cart_shipments' => CartShippingResource::collection($cart_shippings),
            'cart' => new CartResource(Cart::content()),
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
        //
    }
}
