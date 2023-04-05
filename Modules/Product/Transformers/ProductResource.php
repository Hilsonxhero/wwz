<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Product\Transformers\App\ProductReviewResource;
use Modules\Product\Transformers\Panel\ProductGalleryResource;
use Modules\Shipment\Transformers\Panel\DeliveryResource;

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
            'is_wish' => $this->is_wish,
            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'delivery' => new DeliveryResource($this->delivery),
            'brand' => $this->brand->id,
            'combinations' =>  $this->grouped_combinations,
            'features' => $this->grouped_features,
            'default_variant' => new ProductVariantResource($this->defaultVariant),
            'variants' =>  ProductVariantResource::collection($this->variants),
            'review' => array(
                'content' => $this->review,
                'items' => ProductReviewResource::collection($this->reviews),
            ),
            'short_review' => truncate($this->review, 25),
            'status' => $this->status,
            'media' => [
                'main' => $this->getFirstMediaUrl('main'),
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),

                'galleries' => ProductGalleryResource::collection($this->images)
            ],
            'rating' => round($this->scores_avg_value),
            'comments_count' => $this->comments_count
        ];
    }
}
