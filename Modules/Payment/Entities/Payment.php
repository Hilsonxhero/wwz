<?php

namespace Modules\Payment\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING_STATUS = 'pending';
    const SUCCESS_STATUS = 'success';
    const CANCELED_STATUS = 'canceled';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [
        self::PENDING_STATUS, self::SUCCESS_STATUS,
        self::CANCELED_STATUS, self::REJECTED_STATUS
    ];

    protected $fillable = [
        'user_id', 'payment_method_id', 'gateway_id', 'paymentable_type',
        'paymentable_id', 'reference_code', 'amount', 'status', 'ref_num'
    ];


    public static function booted()
    {
        static::saving(function ($payment) {
            $payment->reference_code = Str::random(8);
        });
    }

    public function paymentable()
    {
        return $this->morphTo();
    }
}
