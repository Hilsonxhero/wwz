<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payment\Database\factories\PaymentMethodFactory;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'type', 'status', 'is_default',
    ];


    protected static function newFactory()
    {
        return PaymentMethodFactory::new();
    }
}
