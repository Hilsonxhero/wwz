<?php

namespace Modules\Product\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\App\Recommendation\ProductResource;

class RecommendationProductResource extends JsonResource
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
            'recommendation_id' => $this->recommendation_id,
            'product_id' => $this->product_id,
            'recommendation' => $this->recommendation->category->title,
            'product' => new ProductResource($this->product),
        ];
    }
}
