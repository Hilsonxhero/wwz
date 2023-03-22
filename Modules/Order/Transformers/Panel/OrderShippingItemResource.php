<?php

namespace Modules\Order\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\ProductVariantResource;

class OrderShippingItemResource extends JsonResource
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
            'quantity' => $this->quantity,
            'price' => $this->price,
            'product' => new ProductResource($this->product),
            'variant' => new ProductVariantResource($this->variant),
        ];
    }
}
