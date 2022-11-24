<?php

namespace Modules\Cart\Services\Shipping;

use Modules\Cart\Facades\Cart;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\Shipment\Repository\ShipmentRepositoryInterface;
use Modules\Shipment\Transformers\Panel\ShipmentDateResource;
use Modules\Shipment\Repository\ShipmentCityRepositoryInterface;

class Shipping
{

    private $user;
    private $cart;
    private $cart_items;
    private $shipmentRepo;
    private $shipmentCityRepo;

    public function __construct(ShipmentRepositoryInterface $shipmentRepo, ShipmentCityRepositoryInterface $shipmentCityRepo)
    {
        $this->shipmentRepo = $shipmentRepo;
        $this->shipmentCityRepo = $shipmentCityRepo;
        $cart_content = Cart::content();
        $this->user = auth()->user();
        $this->cart = $cart_content;
        $this->cart_items = collect($cart_content->items)->toArray(true);
    }

    public function content()
    {
        // return "Ww";
        $data = collect($this->cart_items)->groupBy('product.delivery')->transform(function ($item, $delivery) {

            $default_shipment =  $this->shipmentRepo->default($delivery);
            $shipment = $this->shipmentCityRepo->shipment($delivery);
            $shipping =  !is_null($shipment)  ? $shipment->shipment : $default_shipment;
            $shipping = (object) array(
                'id' => $shipping->id,
                'title' => $shipping->title,
                'description' => $shipping->description,
                'shipping_cost' => $shipping->shipping_cost,
                'has_interval_scope' => false,
                'time_scopes' => array(),
            );
            if (!is_null($shipment)) {
                $shipping->has_interval_scope = $shipment->has_interval_scope;
                if ($shipping->has_interval_scope)  $shipping->time_scopes = ShipmentDateResource::collection($shipment->dates);
            }
            return ['delivery_id' => $delivery, 'submit_type' =>  $shipping, 'cart_items' =>  $item];
        })->values();



        $grouped = $data->groupBy('submit_type.id')->map(function ($item2, $key) {
            return (object) array(
                'delivery_id' =>  json_decode(json_encode(...$item2->pluck('delivery_id')), false),
                'package_price' => collect(array_merge(...$item2->pluck('cart_items')->toArray()))->sum('subtotal'),
                'submit_type' => (object) json_decode(json_encode(...$item2->pluck('submit_type')), false),
                'cart_items' => (object) array_merge(...$item2->pluck('cart_items')->toArray())
            );
        })->values();

        return $grouped;
    }
}
