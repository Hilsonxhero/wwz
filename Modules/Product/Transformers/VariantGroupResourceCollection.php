<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VariantGroupResourceCollection extends ResourceCollection
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
                    'name' => $data->name,
                    'type' => $data->type
                ];
            })
        ];
    }
}
