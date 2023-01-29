<?php

namespace Modules\Payment\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Payment\Enums\PaymentStatus as EnumsPaymentStatus;

class PaymentStatus implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        switch ($model->status) {
            case EnumsPaymentStatus::Pending->value:
                return "در انتظار پرداخت ";
                break;
            case EnumsPaymentStatus::Success->value:
                return "موفقیت آمیز";
                break;
            case EnumsPaymentStatus::Canceled->value:
                return "لغو شده";
                break;
            case EnumsPaymentStatus::Rejected->value:
                return "رد شده";
                break;

            default:
                return "رد شده";
                break;
        };
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
