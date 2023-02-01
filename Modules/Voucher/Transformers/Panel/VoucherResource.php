<?php

namespace Modules\Voucher\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'code' => $this->code,
            'value' => round($this->value),
            'minimum_spend' => round($this->minimum_spend),
            'maximum_spend' => round($this->maximum_spend),
            'usage_limit_per_voucher' => $this->usage_limit_per_voucher,
            'usage_limit_per_customer' => $this->usage_limit_per_customer,
            'used' => $this->used,
            'is_percent' => $this->is_percent,
            'is_active' => $this->is_active,
            'start_date' => formatGregorian($this->start_date),
            'end_date' => formatGregorian($this->end_date),
        ];
    }
}
