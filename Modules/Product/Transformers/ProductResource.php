<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;

class ProductResource extends JsonResource
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
            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'category' => $this->category->id,
            'brand' => $this->brand->id,
            'variants' => new ProductVariantResourceCollection($this->variants),
            'review' => $this->review,
            'short_review' => truncate($this->review, 25),
            'status' => $this->status,
            'media' => [
                'main' => $this->getFirstMediaUrl('main'),
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),
                'thumbs' => $this->getMedia('thumbs')->toArray()
            ],
        ];
    }
}
