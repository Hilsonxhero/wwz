<?php

namespace Modules\Setting\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $option = is_null(json_decode(json_decode($this->value))) ? json_decode($this->value) : json_decode(json_decode($this->value));
        return $this->getFirstMediaUrl('main') ?: $option;
    }
}
