<?php

namespace Modules\Product\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceHistoryResource extends JsonResource
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
            'seller_id' => $this->seller_id,
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'date' =>  formatGregorian($this->created_at),
        ];
    }
}
