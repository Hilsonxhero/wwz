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
                    'category' => $data->category,
                    'brand' => $data->category,
                    'review' => $data->review,
                    'short_review' => truncate($data->review, 25),
                    'status' => $data->status,
                    'media' => [
                        'main' => $data->getFirstMediaUrl('main'),
                        'thumb' => $data->getFirstMediaUrl('main', 'thumb'),
                        'thumbs' => $data->getMedia('thumbs'),
                    ],
                ];
            })
        ];
    }
}
