<?php

namespace Modules\Setting\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingBannerResource extends JsonResource
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
            'title' => $this->title,
            'url' => $this->url,
            'position' => $this->position,
            'status' => $this->status,
            'type' => $this->type,
            'page' => [
                'id' => $this->bannerable->id,
                'title' => $this->bannerable->title,
            ],
            'banner' =>  $this->getFirstMediaUrl('main'),
            'mobile_banner' => $this->getFirstMediaUrl('mobile'),
            // 'banner' => isMobile() ? $this->getFirstMediaUrl('mobile') :  $this->getFirstMediaUrl('main'),

        ];
    }
}
