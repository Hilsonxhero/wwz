<?php

namespace Modules\Product\Entities;

use Laravel\Scout\Searchable;
use Spatie\Image\Manipulations;
use Modules\Brand\Entities\Brand;
use Spatie\MediaLibrary\HasMedia;
use Modules\Voucher\Entities\Voucher;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Modules\Shipment\Entities\Delivery;
use Modules\Shipment\Entities\Shipment;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Hilsonxhero\ElasticVision\Application\Explored;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Entities\CommentScore;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia, Explored
{
    use HasFactory, Sluggable, SoftDeletes, InteractsWithMedia, Searchable;

    protected $fillable = [
        'title_fa',
        'title_en',
        'slug',
        'review',
        'category_id',
        'brand_id',
        'status',
        'delivery_id',
    ];

    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'title_fa' => [
                'type' => 'text',
                // 'analyzer' => 'synonym',
            ],
            'status' => [
                'type' => 'text',
                // 'analyzer' => 'synonym',
            ],
            'category' => 'nested',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title_fa' => $this->title_fa,
            'status' => $this->status,
            'category' => $this->category
        ];
    }


    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'products';
    }


    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function questions()
    {
        return $this->morphMany(ProductQuestion::class, 'questionable');
    }

    public function combinations()
    {
        return $this->hasManyThrough(ProductVariantCombination::class, ProductVariant::class);
    }

    public function scores()
    {
        return $this->hasManyThrough(CommentScore::class, Comment::class, 'commentable_id', 'comment_id');
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
            ->keepOriginalImageFormat()
            ->width(300)
            ->height(300)
            ->format(Manipulations::FORMAT_PNG);
    }
    /**
     * Calculate default delivery.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function defaultShipment(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Shipment::query()->where('is_default', true)->where('delivery_id', $this->delivery->id)->first()
        );
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

    /**
     * Get all of the vouchers.
     */
    public function vouchers()
    {
        return $this->morphToMany(Voucher::class, 'voucherable');
    }
}
