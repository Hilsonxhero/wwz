<?php

namespace Modules\Order\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Transformers\Panel\PaymentMethodResource;
use Modules\Payment\Transformers\Panel\PaymentResource;
use Modules\User\Transformers\UserResource;

class OrderResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'payment_method' => new PaymentMethodResource($this->payment_method),
            'reference_code' => $this->reference_code,
            'status' => $this->status,
            'payable_price' => round($this->payable_price),
            'is_returnable' => $this->is_returnable,
            'payments' => PaymentResource::collection($this->payments),
            'price' => json_decode($this->price),

        ];
    }
}
