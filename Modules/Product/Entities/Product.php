<?php

namespace Modules\Product\Entities;

use Modules\Brand\Entities\Brand;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipment\Entities\DeliveryType;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, Sluggable, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title_fa', 'title_en', 'slug', 'review', 'category_id', 'brand_id', 'status', 'delivery_type_id',
    ];

    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


    public function delivery_type()
    {
        return $this->belongsTo(DeliveryType::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_features');
    }

    public function productFeatures()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function incredibles()
    {
        return $this->hasMany(IncredibleProduct::class);
    }

    public function featureValues()
    {
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
            ->width(300)
            ->height(300);
    }

    /**
     * Calculate discount percent.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function defaultVariant(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->variants()->where('default_on', 1)->first(),
        );
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
