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

        // $variant = new ProductVariantResource(resolve(ProductVariantRepositoryInterface::class)->find($this->variant));

        return [
            'id' => $this->id,
            'uuid' => $this->rowId,
            'quantity' => $this->quantity,
            'product' => new ProductResource($this->product),
            'variant' => new ProductVariantResource($this->variant),
            'price' => round($this->price),
            'subtotal' => ($this->variant->price * $this->quantity - round($this->variant->price * ($this->variant->calculate_discount / 100) * $this->quantity)),
            'total' => $this->variant->price * ($this->quantity),
            'discount' => $this->variant->price * ($this->variant->calculate_discount / 100),
            'discount_total' => round($this->variant->price * ($this->variant->calculate_discount / 100) * $this->quantity),
        ];
    }
}
