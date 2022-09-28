<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantCombinationResource extends JsonResource
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
            'variant_id' => $this->variant_id,
            'label' => $this->variant->name,
            'value' => $this->variant->value,
            'type' => $this->variant->group->type,
            'group' => $this->variant->group,
        ];
    }
}
