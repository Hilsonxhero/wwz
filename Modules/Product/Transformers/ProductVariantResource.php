<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'product' => $this->product->id,
            'warranty' => [
                'id' => $this->warranty->id ?? null,
                'title' => $this->warranty->title ?? null,
            ],
            'shipment' => [
                'id' => $this->shipment->id ?? null,
                'title' => $this->shipment->title ?? null,
            ],
            'price' => $this->price,
            'stock' => $this->stock,
            'order_limit' => $this->order_limit,
            'discount' => $this->discount,
            'discount_price' => $this->discount_price,
            'discount_expire_at' => \Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i', strtotime($this->discount_expire_at)),
            'weight' => $this->weight,
            'shipment_price' => 0,
            'combinations' => new ProductVariantCombinationResourceCollection($this->combinations),
        ];
    }
}
