<?php

namespace Modules\Order\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Order\Enums\OrderStatus as EnumsOrderStatus;

class OrderStatus implements CastsAttributes
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
            case EnumsOrderStatus::WaitPayment->value:
                return "در انتظار پرداخت ";
                break;
            case EnumsOrderStatus::Accepted->value:
                return "تایید سفارش";
                break;
            case EnumsOrderStatus::Confirmation->value:
                return "آماده سازی سفارش";
                break;
            case EnumsOrderStatus::Processed->value:
                return "پردازش سفارش";
                break;
            case EnumsOrderStatus::LeavingCenter->value:
                return "خروج از مرکز پردازش";
                break;
            case EnumsOrderStatus::ReceivedCenter->value:
                return "دریافت در مرکز توضیح";
                break;
            case EnumsOrderStatus::DeliveryDispatcher->value:
                return "تحویل به مامور ارسال";
                break;
            case EnumsOrderStatus::DeliveryCustomer->value:
                return "تحویل مرسوله به مشتری";
                break;
            case EnumsOrderStatus::CanceledSystem->value:
                return "سفارش لغو شده";
                break;
            case EnumsOrderStatus::Sent->value:
                return "تحویل  شده";
                break;

            default:
                return "سفارش لغو شده";
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
