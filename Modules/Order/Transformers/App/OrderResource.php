<?php

namespace Modules\Order\Transformers\App;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Transformers\Panel\PaymentResource;
use Modules\Order\Transformers\Panel\OrderShippingResource;
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
            'address' => $this->cart->address,
            'shipments' => OrderShippingResource::collection($this->shippings),
            'user' => new UserResource($this->user),
            'payment_method' => new PaymentMethodResource($this->payment_method),
            'reference_code' => $this->reference_code,
            'payable_price' => round($this->payable_price),
            'remaining_amount' => round($this->remaining_amount),
            'payment_remaining_time' => $this->remaining_time_seconds * 1000,
            'is_returnable' => $this->is_returnable,
            'status' => $this->status,
            'status_fa' => $this->order_status,
            'payments' => PaymentResource::collection($this->payments),
            'price' => json_decode($this->price),
            'create_at' => formatGregorian($this->created_at, '%A, %d %B'),
        ];
    }
}
