<?php

namespace Modules\Cart\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;

class CartItemsResource extends JsonResource
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
            'id' => $this->rowId,
            'product' => $this->product,
            'variant' => $this->variant,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'discount' => $this->discount,
            'quantity' => $this->qty,
        ];
    }
}
