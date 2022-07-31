<?php

namespace Modules\Brand\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrandCollectionResource extends ResourceCollection
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
                    'title' => $data->title,
                    'title_en' => $data->title_en,
                    'slug' => $data->slug,
                    'link' => $data->link,
                    'description' => $data->description,
                    'short_review' => truncate($data->description, 25),
                    'category' => $data->category,
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
