<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
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
            'delivery_type' => new DeliveryResource($this->delivery_type),
            'brand' => $this->brand->id,
            'combinations' =>  collect($this->combinations)->unique('variant_id')->groupBy('variant.group')->transform(function ($item, $key) {
                return ['group' => json_decode($key), 'values' => $item->transform(function ($combination, $key) {
                    $combination->variant->combination_id = $combination->id;
                    return $combination->variant;
                })];
            })->values(),
            'features' => $this->productFeatures ? collect($this->productFeatures)->groupBy('feature.parent.title')->transform(function ($item, $key) {
                return ['feature' => $key, 'values' => $item->mapToGroups(function ($item) {
                    return [$item['feature']['title'] => $item['value']];
                })->transform(function ($xx, $uu) {
                    return ['title' => $uu, 'values' => $xx];
                })];
            })->all() : null,
            'default_variant' => new ProductVariantResource($this->default_variant),
            'variants' =>  ProductVariantResource::collection($this->variants),
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
