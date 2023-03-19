<?php

namespace Modules\Product\Transformers\App;

use Morilog\Jalali\Jalalian;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Product\Transformers\ProductVariantResource;

class IncredibleProductSearchEngineResource extends JsonResource
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
            'id' => $this->id,
            'is_wish' => $this->is_wish,
            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'category' => new CategoryResource($this->category),
            'brand' => $this->brand->id,
            'default_variant' => new ProductVariantResource($this->default_variant),
            'status' => $this->status,
            'rating' => round($this->scores_avg_value),
            'comments_count' => $this->comments_count
        ];
    }
}
