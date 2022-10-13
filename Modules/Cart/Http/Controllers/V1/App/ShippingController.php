<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\Shipment\Entities\ShipmentType;
use Modules\Shipment\Entities\ShipmentTypeDate;
use Modules\Cart\Transformers\App\ShippingResource;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\Shipment\Transformers\App\ShippingSubmitTypeResource;

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

        $data = collect($cart_items)->groupBy('options.delivery')->transform(function ($item, $key) use ($user) {
            $ww = ShipmentTypeDate::query()->where('delivery_type_id', $key)->where('city_id', $user->default_address->city_id)->with('shipment_type')->first();
            if (is_null($ww)) {
                $ww = ShipmentType::query()->where('is_default', true)->first();
            } else {
                $ww = $ww->shipment_type;
            }
            return ['submit_type' => new ShippingSubmitTypeResource($ww), 'cart_items' => $item->transform(function ($cart_item, $key) {
                return new CartItemsResource($cart_item);
            })];
        })->values();

        $content = (object) [
            'items' => $data,
        ];

        // return  $data;

        return new ShippingResource($content);
    }
}
