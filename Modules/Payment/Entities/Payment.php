<?php

namespace Modules\Payment\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Entities\User;

class Payment extends Model
{
    use HasFactory, SoftDeletes;



    protected $fillable = [
        'user_id', 'payment_method_id', 'gateway_id', 'paymentable_type',
        'paymentable_id', 'reference_code', 'amount', 'status', 'ref_num', 'invoice_id'
    ];


    public static function booted()
    {
        static::saving(function ($payment) {
            $payment->reference_code =  random_int(1000000, 10000000);
        });
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
