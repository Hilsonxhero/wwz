<?php

namespace Modules\Product\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Transformers\CategoryResource;

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
            'category' => new CategoryResource($this->category),
            'title'  => $this->category->title,
            'description'  => $this->description,
            'products'  => RecommendationProductResource::collection($this->products),
        );
    }
}
