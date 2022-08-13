<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipment\Entities\Shipment;
use Modules\Warranty\Entities\Warranty;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'warranty_id', 'price', 'discount', 'discount_price', 'stock',
        'weight', 'order_limit', 'default_on', 'shipment_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function combinations()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }
}
