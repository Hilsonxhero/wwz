<?php

namespace Modules\User\Transformers;

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
            'role' => $this->roles()->first()->name ?? null,
            'city' => $this->city->name ?? null,
            'state' => $this->city->state->name ?? null,
            'city_id' => $this->city->id ?? null,
            'wallet' => $this->wallet,
            'point' => $this->point,
            'national_identity_number' => $this->national_identity_number,
            'cart_number' => $this->cart_number,
            'status' => $this->status,
            'avatar' => $this->getFirstMediaUrl('avatar', 'thumb')
        ];
    }
}
