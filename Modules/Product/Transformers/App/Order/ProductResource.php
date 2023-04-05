<?php

namespace Modules\Product\Transformers\App\Order;

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
            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'delivery' => new DeliveryResource($this->delivery),
            'status' => $this->status,
            'media' => [
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),
                'base64_encode' => "data:image/png;base64," . base64_encode(file_get_contents(asset($this->getFirstMediaUrl('main', 'thumb')))),

            ],
            'rating' => round($this->scores_avg_value),
        ];
    }
}
