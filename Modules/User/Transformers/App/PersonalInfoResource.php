<?php

namespace Modules\User\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalInfoResource extends JsonResource
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
            'user' => new UserResource($this),
        ];
    }
}
