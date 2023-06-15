<?php

namespace Modules\Product\Transformers\App\BestSelling;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Seller\Transformers\v1\App\SellerResource;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Shipment\Transformers\Panel\DeliveryResource;
use Modules\Product\Transformers\App\ProductReviewResource;
use Modules\Product\Transformers\Panel\ProductGalleryResource;

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
            'default_variant' => new ProductVariantResource($this->defaultVariant),
            'status' => $this->status,
            'media' => [
                'main' => $this->getFirstMediaUrl('main'),
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),
                'galleries' => ProductGalleryResource::collection($this->images)
            ],
        ];
    }
}
