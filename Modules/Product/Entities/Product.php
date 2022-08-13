<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Brand\Entities\Brand;
use Modules\Category\Entities\Category;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, Sluggable, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title_fa', 'title_en', 'slug', 'review', 'category_id', 'brand_id', 'status',
    ];



    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


    // protected static function newFactory()
    // {
    //     return \Modules\Product\Database\factories\ProductFactory::new();
    // }


    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_features');
    }

    public function featureValues()
    {
        // return $this->belongsToMany(FeatureValue::class, 'product_features')->withPivot('value');

        return $this->hasMany(ProductFeature::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function combinations()
    {
        return $this->hasManyThrough(ProductVariantCombination::class, ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public static function last()
    {
        return static::all()->last();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title_fa'
            ]
        ];
    }
}
