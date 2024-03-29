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
            'date' => !is_null($this->date) ? formatGregorian($this->date, '%A, %d %B') : $this->shipment->delivery_date,
            'status' => $this->status,
            'status_fa' => $this->order_status,
            'reference_code' => $this->reference_code,
        ];
    }
}
