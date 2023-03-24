<?php

namespace Modules\Product\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\ProductVariantResource;

class AnnouncementResource extends JsonResource
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
            'product' => new ProductResource($this->variant->product),
            'variant' => new ProductVariantResource($this->variant),
            'type' => $this->type,
            'via_sms' => $this->via_sms,
            'via_email' => $this->via_email,
        ];
    }
}
