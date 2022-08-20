<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class IncredibleProductResource extends JsonResource
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
            'id' => $this->product->id,
            'title_fa' => $this->product->title_fa,
            'title_en' => $this->product->title_en,
            'slug' => $this->product->slug,
            'discount' => $this->discount,
            'discount_expire_at' =>  \Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i', strtotime($this->discount_expire_at)),
            'time' => $this->discount_expire_at->diffInSeconds(now()),
            'variant' => new ProductVariantResource($this->variant),
            'media' => [
                'main' => $this->product->getFirstMediaUrl('main')
            ],

        ];
    }
}
