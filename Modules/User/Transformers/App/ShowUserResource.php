<?php

namespace Modules\User\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cart\Services\Cart\Facades\Cart;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\Cart\Transformers\App\CartResource;

class ShowUserResource extends JsonResource
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
            'user' => auth()->user(),
            'is_logged_in' => !!auth()->user(),
            'cart' => new CartResource(auth()->user()->cart()),
        ];
    }
}
