<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\Shipment\Entities\Shipment;
use Modules\User\Transformers\UserResource;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Shipment\Entities\ShipmentCity;
use Modules\Shipment\Entities\ShipmentDate;
use Modules\Cart\Transformers\App\ShippingResource;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\User\Transformers\App\UserAddressResource;
use Modules\Shipment\Transformers\Panel\ShipmentDateResource;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function init()
    {
        $cart_items = Cart::content()->items;

        $user = auth()->user();




        // TODO:REFACTOR

        $data = collect($cart_items)->groupBy('options.delivery')->transform(function ($item, $key) use ($user) {
            $default_shipment =  Shipment::query()->where('is_default', true)->where('delivery_id', $key)->first();

            $shipment = ShipmentCity::query()->where('delivery_id', $key)->where('city_id', $user->default_address->city_id)->with('shipment')->first();

            // dump($shipment);
            // && $shipment->has_interval_scope
            $shipment_type =  !is_null($shipment)  ? $shipment->shipment : $default_shipment;

            $submit_type = (object) array(
                'id' => $shipment_type->id,
                'title' => $shipment_type->title,
                'description' => $shipment_type->description,
                'shipping_cost' => $shipment_type->shipping_cost,
                'has_interval_scope' => false,
                'time_scopes' => array(),
            );

            if (!is_null($shipment)) {
                $submit_type->has_interval_scope = $shipment->has_interval_scope;
                if ($submit_type->has_interval_scope)  $submit_type->time_scopes = ShipmentDateResource::collection($shipment->dates);
            }

            return ['submit_type' => (array) $submit_type, 'cart_items' => $item->transform(function ($cart_item, $key) {
                return new CartItemsResource($cart_item);
            })->toArray()];
        })->values();

        // return $data;

        $grouped2 = $data->groupBy('submit_type.id')->map(function ($item2) {
            return ['submit_type' => array_merge(...$item2->pluck('submit_type')), 'items' => array_merge(...$item2->pluck('cart_items'))];
        })->values();

        // return $grouped2;



        // $grouped = collect($data)->mapToGroups(function ($item, $key) {
        //     return [$item['submit_type']->id  =>  'cart_items'];
        // });

        // return $grouped->all();

        $content = (object) [
            'items' => $grouped2,
        ];

        $content = [
            'packages' => $grouped2,
            'packages_count' => count($grouped2),
            'default_address' => new UserAddressResource($user->default_address),
            'cart' => new CartResource(Cart::content()),
            'user' => new UserResource($user),
        ];

        ApiService::_success($content);

        // return  $data;

        // return new ShippingResource($content);
    }
}
