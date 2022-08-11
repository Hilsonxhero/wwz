<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductFeatureResourceCollection extends ResourceCollection
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
                    'value' => $data->quantity->title ?? $data->value,
                    'feature' => $data->feature->title,
                ];
            })
        ];
    }
}
