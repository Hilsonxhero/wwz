<?php

namespace Modules\Warranty\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarrantyResourceCollection extends ResourceCollection
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
                    'description' => $data->description,
                    'status' => $data->status,
                ];
            })
        ];
    }
}
