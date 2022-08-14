<?php

namespace Modules\State\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'name' => $this->name,
            'zone_code' => $this->zone_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'city_fast_sending' => $this->city_fast_sending,
            'pay_at_place' => $this->pay_at_place,
            'status' => $this->status,
            'state' => $this->state,
        ];
    }
}
