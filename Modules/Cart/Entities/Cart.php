<?php

namespace Modules\Cart\Entities;

use Modules\Voucher\Entities\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'identifier', 'instance', 'address', 'status'
    ];

    protected $casts = [
        'address' => 'json'
    ];


    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }

    /**
     * Get all of the vouchers.
     */
    // public function voucher()
    // {
    //     return $this->morphToMany(Voucher::class, 'voucherable');
    // }
}
