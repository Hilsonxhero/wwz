<?php

namespace Modules\Voucher\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucherable extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id', 'voucherable_id', 'voucherable_type',
    ];
}
