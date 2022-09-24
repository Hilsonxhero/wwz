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
            'items_count' => $this->items_count,
            'payable_price' => $this->payable_price,
            'rrp_price' => $this->rrp_price,
            'cart_items' =>  CartItemsResource::collection($this->items)
        ];
    }
}
