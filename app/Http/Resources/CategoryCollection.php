<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Category\Entities\Category;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'link' => $data->link,
                    'description' => $data->description,
                    'parent' => $data->parent_id,
                    'status' => $data->status,
                    'media' => [
                        'main' => $data->getFirstMediaUrl(),
                        'thumb' => $data->getFirstMediaUrl('default', 'thumb')
                    ],
                ];
            })
        ];
    }
}
