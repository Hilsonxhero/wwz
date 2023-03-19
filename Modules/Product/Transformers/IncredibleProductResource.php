<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
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
            'category' => new CategoryResource($this->product->category),
            'default_variant' => new ProductVariantResource($this->variant),
            'discount_diff_seconds' => $this->discount_diff_seconds * 1000,
            'media' => [
                'thumb' =>  $this->product->getFirstMediaUrl('main', 'thumb')
            ],
        ];
    }
}
