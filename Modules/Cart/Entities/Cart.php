<?php

namespace Modules\Cart\Entities;

use Modules\Voucher\Entities\Voucher;
use Illuminate\Database\Eloquent\Model;
use Modules\Cart\Database\factories\CartFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'identifier', 'instance', 'address', 'config', 'status'
    ];

    protected $casts = [
        'address' => 'json',
        'config' => 'object'
    ];


    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }

    protected function shippingCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->shippings->reduce(function ($carry, $item) {
                return $carry + $item->cost;
            })
        );
    }

    /**
     * Get all of the vouchers.
     */
    // public function voucher()
    // {
    //     return $this->morphToMany(Voucher::class, 'voucherable');
    // }

    protected static function newFactory()
    {
        return CartFactory::new();
    }
}
