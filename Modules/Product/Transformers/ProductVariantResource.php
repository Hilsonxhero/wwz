<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Seller\Transformers\v1\App\SellerResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'seller' => new SellerResource($this->seller),
            'has_stock' => $this->has_stock,
            'shipment' => [
                'id' => $this->shipment->id,
                'title' => $this->shipment->title,
            ],
            'is_announcemented_promotion' => $this->is_announcemented_promotion,
            'is_announcemented_availability' => $this->is_announcemented_availability,
            'product' => $this->product->id,
            'warranty' => [
                'id' => $this->warranty->id,
                'title' => $this->warranty->title,
            ],
            'price' => round($this->price),
            'is_incredible' => !!$this->incredible,
            'is_promotion' => !!$this->is_promotion,
            'time' => $this->calculate_discount_diff_seconds,
            'rrp_price' => round($this->price),
            'stock' => $this->stock,
            'order_limit' => $this->order_limit,
            'default_on' => $this->default_on,
            'discount' => $this->calculate_discount,
            'selling_price' => round($this->calculate_discount_price),
            'discount_expire_at' => formatGregorian($this->discount_expire_date),
            'weight' => round($this->weight),
            'shipment_price' => 0,
            'combinations' =>  ProductVariantCombinationResource::collection($this->combinations),
        ];
    }
}
