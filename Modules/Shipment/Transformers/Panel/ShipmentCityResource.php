<?php

namespace Modules\Shipment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\State\Transformers\CityResource;

class ShipmentCityResource extends JsonResource
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
            'city' => new CityResource($this->city),
            'shipment' => new ShipmentTypeResource($this->shipment),
            'delivery' => new DeliveryTypeResource($this->delivery),
        ];
    }
}
