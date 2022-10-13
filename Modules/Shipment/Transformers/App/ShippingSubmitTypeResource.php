<?php

namespace Modules\Shipment\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shipment\Transformers\Panel\ShipmentDateResource;

class ShippingSubmitTypeResource extends JsonResource
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
            'time_scopes' => ShipmentDateResource::collection($this->dates)
        ];
    }
}
