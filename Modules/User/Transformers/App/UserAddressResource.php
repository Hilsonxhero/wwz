<?php

namespace Modules\User\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'username' => $this->user->username,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'telephone' => $this->telephone,
            'mobile' => $this->mobile,
            'is_default' => $this->is_default,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'building_number' => $this->building_number,
            'unit' => $this->unit,
        ];
    }
}
