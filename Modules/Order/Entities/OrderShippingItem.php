<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

class OrderShippingItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'variant_id', 'quantity', 'returned_quantity',
        'cancelled_quantity', 'price', 'order_shipping_id',
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
