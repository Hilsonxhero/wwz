<?php

namespace Modules\Cart\Transformers\App;

use Modules\Cart\Facades\Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\App\AddressResource;
use Modules\User\Transformers\App\UserAddressResource;
use Modules\User\Transformers\UserResource;

class ShippingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->user();
        return [
            'packages' => $this->items,
            'packages_count' => count($this->items),
            'default_address' => new UserAddressResource($user->default_address),
            'cart' => new CartResource(Cart::content()),
            'user' => new UserResource($user),
        ];
    }
}
