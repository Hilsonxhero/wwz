<?php

namespace Modules\Category\Transformers;

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
            'title' => $this->title,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'link' => $this->link,
            'description' => $this->description,
            'short_review' => truncate($this->description, 25),
            'main_parent' => $this->main_parent,
            'status' => $this->status,
            'media' => [
                'main' => $this->getFirstMediaUrl(),
                'thumb' => $this->getFirstMediaUrl('default', 'thumb')
            ],
        ];
    }
}
