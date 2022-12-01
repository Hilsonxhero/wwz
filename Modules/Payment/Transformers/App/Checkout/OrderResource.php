<?php

namespace Modules\Payment\Transformers\App\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Transformers\Panel\PaymentMethodResource;

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
            'cart_id' => $this->cart_id,
            'create_at' => formatGregorian($this->created_at, '%A, %d %B'),
            'payable_price' => $this->payable_price,
            'status' => $this->status,
            'reference_code' => $this->reference_code,
            'payment_method' => new PaymentMethodResource($this->payment_method)
        ];
    }
}
