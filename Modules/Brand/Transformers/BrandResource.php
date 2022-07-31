<?php

namespace Modules\Brand\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'category' => $this->category,
            'status' => $this->status,
            'special' => $this->is_special,
            'media' => [
                'main' => $this->getFirstMediaUrl(),
                'thumb' => $this->getFirstMediaUrl('default', 'thumb')
            ],
        ];
    }
}
