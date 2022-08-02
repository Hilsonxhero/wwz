<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = ['feature_id', 'product_id', 'feature_value_id'];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductFeatureFactory::new();
    }
}
