<?php

namespace Modules\Voucher\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Voucher\Casts\VoucherableTitle;
use Modules\Voucher\Casts\VoucherableType;

class Voucherable extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id', 'voucherable_id', 'voucherable_type',
    ];


    protected $casts = [
        'voucherable_title' => VoucherableTitle::class,
        'type' => VoucherableType::class
    ];



    public function voucherable()
    {
        return $this->morphTo();
    }
}
