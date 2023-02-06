<?php

namespace Modules\Payment\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shipment\Transformers\App\ShipmentIntervalResource;
use Modules\Shipment\Transformers\ShipmentResource;

class CartShippingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cart_items' => CartShippingItemResource::collection($this->cart_items),
            'package_price' => round($this->package_price),
            'shipment' => new ShipmentResource($this->shipment),
            'cost' => round($this->cost),
            'delivery_date' => new ShipmentIntervalResource($this->interval),
        ];
    }
}
