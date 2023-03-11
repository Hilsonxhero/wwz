<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Scout\Searchable;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = ['feature_id', 'product_id', 'feature_value_id', 'value'];

    public $timestamps = false;

    public $incrementing = false;

    public static function booted()
    {
        static::saved(function ($model) {
            $model->product()->searchable();
        });
        static::deleted(function ($model) {
            $model->product()->searchable();
        });
    }

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

    /**
     * Calculate discount percent.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->quantity ?  $this->quantity->title : $this->attributes['value'],
        );
    }
}
