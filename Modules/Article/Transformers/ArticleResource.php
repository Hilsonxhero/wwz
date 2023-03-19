<?php

namespace Modules\Article\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ],
            'slug' => $this->slug,
            'content' => $this->content,
            'description' => $this->description,
            'short_link' => $this->short_link,
            'status' => $this->status,
            'created_at' => formatGregorian($this->created_at, '%A, %d %B'),
            'media' => [
                'main' => $this->getFirstMediaUrl('main'),
                'thumb' => $this->getFirstMediaUrl('main', 'thumb'),
            ],
        ];
    }
}
