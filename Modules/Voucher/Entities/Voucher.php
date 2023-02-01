<?php

namespace Modules\Voucher\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'value', 'minimum_spend', 'maximum_spend',
        'usage_limit_per_voucher', 'usage_limit_per_customer', 'used',
        'is_percent', 'is_active', 'is_flexable', 'start_date', 'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function voucherables()
    {
        return $this->hasMany(Voucherable::class);
    }
}
