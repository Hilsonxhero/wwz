<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureResource extends JsonResource
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
            'feature' => $this->feature,
            'quantity' => $this->value,
            'value' => $this->feature_value_id,
            'has_feature_value' => !!$this->quantity
        ];
    }
}
