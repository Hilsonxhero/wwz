<?php

namespace Modules\User\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'has_password' => $this->has_password,
            'city_id' => $this->city_id,
            'wallet' => $this->wallet,
            'national_identity_number ' => $this->national_identity_number,

        ];
    }
}
