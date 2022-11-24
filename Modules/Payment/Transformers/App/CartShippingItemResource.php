<?php

namespace Modules\Payment\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\Cart\ProductResource;
use Modules\Product\Transformers\Cart\ProductVariantResource;

class CartShippingItemResource extends JsonResource
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
            'cart_id' => $this->cart_item->cart_id,
            'quantity' => $this->cart_item->quantity,
            'variant' => new ProductVariantResource($this->cart_item->variant),
            'product' => new ProductResource($this->cart_item->product),
        ];
    }
}
