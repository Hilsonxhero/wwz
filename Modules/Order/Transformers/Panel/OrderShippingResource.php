<?php

namespace Modules\Order\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shipment\Transformers\Panel\ShipmentResource;

class OrderShippingResource extends JsonResource
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
            'shipment' => new ShipmentResource($this->shipment),
            'order_items_cost' => $this->total_price,
            'order_items' => OrderShippingItemResource::collection($this->items),
        ];
    }
}
