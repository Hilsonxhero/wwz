<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySlideResource extends JsonResource
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
            'title' => $this->title,
            'url' => $this->url,
            'position' => $this->position,
            'status' => $this->status,
            'type' => $this->type,
            'category' => [
                'id' => $this->slideable->id,
                'title' => $this->slideable->title,
            ],
            'banner' => $this->getFirstMediaUrl(),
        ];
    }
}
