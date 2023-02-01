<?php

namespace Modules\Voucher\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherableResource extends JsonResource
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
            'voucherable_title' => $this->voucherable_title,
            'type' => $this->type,
            // 'voucherable' => $this->voucherable,

        ];
    }
}
