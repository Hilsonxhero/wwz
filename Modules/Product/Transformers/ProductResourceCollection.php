<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'title_fa' => $data->title_fa,
                    'title_en' => $data->title_en,
                    'slug' => $data->slug,
                    'description' => $data->description,
                    'short_review' => truncate($data->description, 25),
                    'parent' => $data->parent,
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
