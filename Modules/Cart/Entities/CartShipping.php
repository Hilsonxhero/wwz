<?php

namespace Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartShipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_id',
        'cart_item_id',
    ];

    public $timestamps = false;

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
    public function cart_item()
    {
        return $this->belongsTo(CartItem::class);
    }
}
