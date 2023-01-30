<?php

namespace Modules\Category\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'products_count' => $this->products_count,
            'title' => $this->title,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'link' => $this->link,
            'description' => $this->description,
            'short_review' => truncate($this->description, 25),
            'parent' => $this->parent,
            'status' => $this->status,
            'children' => CategoryResource::collection($this->children),
            'media' => [
                'main' => $this->getFirstMediaUrl(),
                'thumb' => $this->getFirstMediaUrl('default', 'thumb')
            ],
        ];
    }
}
