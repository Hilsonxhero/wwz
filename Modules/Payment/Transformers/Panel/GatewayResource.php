<?php

namespace Modules\Payment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'config' => $this->config,
            'is_default' => $this->is_default,
            'type' => $this->type,
            'status' => $this->status,
        ];
    }
}
