<?php

namespace Modules\Product\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Product\Transformers\Panel\RecommendationProductResource;

class RecommendationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            'id' => $this->id,
            'title'  => $this->category->title,
            'description'  => $this->description,
            'products'  => RecommendationProductResource::collection($this->products),
        );
    }
}
