<?php

namespace Modules\User\Transformers\Panel;

use Modules\Cart\Facades\Cart;
use Modules\Cart\Transformers\App\CartResource;
use Modules\User\Transformers\App\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
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
            'user' => new UserResource(auth()->user()),
            'is_logged_in' => !!auth()->user(),
        ];
    }
}
