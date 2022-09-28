<?php

namespace Modules\User\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
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
            'access_token' => $this->access_token,
            'expires_in' => $this->expires_in,
            'refresh_token' => $this->refresh_token,
            'token_type' => $this->token_type,
            "success" => true
        ];
    }
}
