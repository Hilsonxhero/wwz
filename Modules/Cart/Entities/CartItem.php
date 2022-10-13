<?php

namespace Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'cart_id',
        'product_id',
        'variant_id',
        'price',
        'quantity',
    ];


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
