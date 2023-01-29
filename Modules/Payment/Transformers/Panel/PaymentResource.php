<?php

namespace Modules\Payment\Transformers\Panel;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Transformers\Panel\PaymentMethodResource;

class PaymentResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'ref_num' => $this->ref_num,
            'reference_code' => $this->reference_code,
            'amount' => $this->amount,
            'status' => $this->status,
            'status_fa' => $this->status_fa,
            'user' => new UserResource($this->user),
            'payment_method' => new PaymentMethodResource($this->payment_method),
            'gateway' => new GatewayResource($this->gateway),
        ];
    }
}
