<?php

namespace Modules\Product\Transformers\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
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
            'status' => $this->status,
            'media' => [
                'main' => $this->getFirstMediaUrl('main'),
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),
                'thumbs' => $this->getMedia('thumbs')->toArray()
            ],
            'delivery' => $this->delivery->id,
            'default_shipment' => $this->default_shipment,
        ];
    }
}
