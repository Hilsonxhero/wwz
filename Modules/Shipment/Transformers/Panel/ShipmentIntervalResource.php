<?php

namespace Modules\Shipment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shipment\Entities\ShipmentDate;

class ShipmentIntervalResource extends JsonResource
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
            // 'shipment_date' =>  new ShipmentDateResource($this->shipment_date),
            'order_capacity' => $this->order_capacity,
            'shipping_cost' => round($this->shipping_cost),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ];
    }
}
