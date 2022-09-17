<?php

namespace Modules\Cart\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'payable_price' => 0,
            'rrp_price' => 0,
            'cart_items' =>  CartItemsResource::collection($this)
        ];
    }
}
