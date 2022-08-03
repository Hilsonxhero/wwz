<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Warranty\Entities\Warranty;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'warranty_id', 'price', 'discount', 'discount_price', 'stock',
        'weight', 'order_limit', 'default_on',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }
}
