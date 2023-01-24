<?php

namespace Modules\Order\Entities;

use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductVariant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderShippingItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'variant_id', 'quantity', 'returned_quantity',
        'cancelled_quantity', 'price', 'order_shipping_id',
    ];


    protected $casts = [
        'price' => 'json'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function shipping()
    {
        return $this->belongsTo(OrderShipping::class, 'order_shipping_id');
    }
}
