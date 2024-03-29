<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\Cart\Facades\Shipping;
use Modules\Shipment\Entities\Shipment;
use Modules\Cart\Transformers\App\ShippingResource;
use Modules\User\Transformers\App\UserAddressResource;
use Modules\Cart\Http\Requests\App\ShippingRequest;
use Modules\Cart\Repository\ShippingRepositoryInterface;
use Modules\Cart\Transformers\App\ShippingCostResource;
use Modules\Shipment\Repository\ShipmentRepositoryInterface;
use Modules\User\Entities\Address;

class ShippingController extends Controller
{
    private $shipmentRepo;
    private $shippingRepo;
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepo,
        ShippingRepositoryInterface $shippingRepo
    ) {
        $this->shipmentRepo = $shipmentRepo;
        $this->shippingRepo = $shippingRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function init()
    {
        $user = auth()->user();

        if (!$user->has_default_address) {
            ApiService::_throw(array("message" => trans('response.defective_profile'), 'status' => 410), 200);
        }

        $user->cart->update([
            'config' => null
        ]);
        $shipping = Shipping::content();

        // return $shipping;

        $content = (object) [
            'items' => $shipping,
        ];
        return new ShippingResource($content);
    }

    public function store(ShippingRequest $request)
    {
        $user = auth()->user();

        $packages_delivery = $request->packages;

        // ApiService::_throw($packages_delivery);

        $address = Address::query()->where('id', $request->address_id)->first();

        $address = new UserAddressResource($address);

        $user->cart->update([
            'address' => json_decode(json_encode($address)),
            'config' => null
        ]);


        $packages_delivery = collect($request->packages);

        // Cart::setShipment(50000);

        $shipping = Shipping::content();


        $content = (object) [
            'items' => $shipping,
        ];

        $packages = new ShippingResource($content);

        $packages = collect($packages);

        // $user->cart->update([
        //     'config->voucher_id' => 6
        // ]);

        $user->shippings()->delete();

        foreach ($packages->get('packages') as $value) {

            $filtered = (object) $packages_delivery->where('delivery_id', $value->delivery_id)->first();

            $data = [
                'user_id' => $user->id,
                'cart_id' => $user->cart->id,
                'shipment_id' => $value->submit_type->id,
                'shipment_interval_id' => $filtered->time_scope ?? null,
                'package_price' => $value->package_price,
                'cost' => $value->submit_type->shipping_cost,
            ];

            $shipping_item = $this->shippingRepo->create($data);

            $shipping_cart_items = array();

            foreach ($value->cart_items as $key => $value) {
                array_push($shipping_cart_items, ['cart_item_id' => $value->id]);
            }
            $shipping_item->cart_items()->createMany($shipping_cart_items);
        }

        ApiService::_success(trans('response.responses.200'));

        $data = [];
    }

    public function cost(Request $request)
    {
        $cart = Cart::content();

        $shipment_ids = array();

        foreach ($request->packages as $key => $package) {
            array_push($shipment_ids, $package['shipment_id']);
        }

        //TODO: refactor query

        $shipments = Shipment::query()->whereIn('id', $shipment_ids)->get();

        $submit_types = ShippingCostResource::collection($shipments);

        $submit_types = $submit_types->toArray(request());

        $shipping_cost = collect($submit_types)->reduce(function ($carry, $item) {
            return $carry + $item['cost'];
        });

        $payable_price = $cart->payable_price + $shipping_cost;

        $data =  (object) array(
            'submit_types' =>  $submit_types,
            'shipping_cost' => $shipping_cost,
            'payable_price' => $payable_price
        );

        ApiService::_success($data);
    }
}
