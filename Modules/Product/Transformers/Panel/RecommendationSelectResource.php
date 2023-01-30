<?php

namespace Modules\Product\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationSelectResource extends JsonResource
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
            'title'  => $this->category->title,
        ];
    }
}
