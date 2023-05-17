<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Seller\Entities\Seller;

class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'product_id',
        'product_variant_id',
        'price',
        'discount_price',
    ];


    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
