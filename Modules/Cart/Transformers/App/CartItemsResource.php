<?php

namespace Modules\Cart\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\Cart\ProductResource;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\Cart\ProductVariantResource;
use Modules\Product\Repository\ProductVariantRepositoryInterface;

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
            'id' => $this->id,
            'uuid' => $this->rowId,
            'quantity' => $this->quantity,
            'product' => new ProductResource(resolve(ProductRepositoryInterface::class)->find($this->product)),
            'variant' => new ProductVariantResource(resolve(ProductVariantRepositoryInterface::class)->find($this->variant)),
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'discount' => $this->discount,
        ];
    }
}
