<?php

namespace Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'identifier', 'instance', 'address'
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
}
