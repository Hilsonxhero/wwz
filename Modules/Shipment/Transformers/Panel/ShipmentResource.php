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
            'description' => $this->description,
            'shipping_cost' => $this->shipping_cost,
            'is_default' => !!$this->is_default,
        ];
    }
}
