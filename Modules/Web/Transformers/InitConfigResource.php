<?php

namespace Modules\Web\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class InitConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
