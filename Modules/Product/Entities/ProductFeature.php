<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = ['feature_id', 'product_id', 'feature_value_id', 'value'];

    public $timestamps = false;

    public $incrementing = false;

    // protected static function newFactory()
    // {
    //     return \Modules\Product\Database\factories\ProductFeatureFactory::new();
    // }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function quantity()
    {
        return $this->belongsTo(FeatureValue::class, 'feature_value_id');
    }
}
