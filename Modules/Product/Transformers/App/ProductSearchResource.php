<?php

namespace Modules\Product\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Transformers\Panel\ProductGalleryResource;


class ProductSearchResource extends JsonResource
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
            'is_wish' => $this->is_wish,
            'has_stock' => $this->has_stock,
            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'brand' => $this->brand->id,
            'default_variant' => new ProductVariantResource($this->default_variant),
            'short_review' => truncate($this->review, 25),
            'status' => $this->status,
            'media' => [
                'main' => $this->getFirstMediaUrl('main'),
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),
                'galleries' => ProductGalleryResource::collection($this->images)
            ],
            'rating' => round($this->scores_avg_value),

        ];
    }
}
