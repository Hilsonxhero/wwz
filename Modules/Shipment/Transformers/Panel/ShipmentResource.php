<?php

namespace Modules\Shipment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
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
            'title' => $this->title,
            'delivery' => new DeliveryResource($this->delivery),
            'description' => $this->description,
            'delivery_date' => $this->delivery_date,
            'shipping_cost' => round($this->shipping_cost),
            'is_default' => !!$this->is_default,
        ];
    }
}
