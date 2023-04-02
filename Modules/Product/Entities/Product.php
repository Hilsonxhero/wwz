<?php

namespace Modules\Product\Entities;

use Laravel\Scout\Searchable;
use Modules\User\Entities\User;
use Spatie\Image\Manipulations;
use Modules\Brand\Entities\Brand;
use Spatie\MediaLibrary\HasMedia;
use Modules\Comment\Entities\Comment;
use Modules\Voucher\Entities\Voucher;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Modules\Shipment\Entities\Delivery;
use Modules\Shipment\Entities\Shipment;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Modules\Comment\Entities\CommentScore;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Hilsonxhero\ElasticVision\Application\Explored;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Hilsonxhero\ElasticVision\Application\IndexSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Order\Entities\OrderShippingItem;
use Modules\Product\Casts\ProductDefaultShipment;
use Modules\Product\Transformers\ProductVariantResource;

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

    // protected $appends = ['default_shipment'];


    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'title_fa' => [
                'type' => 'text',
                // 'analyzer' => 'my_analyzer',
            ],
            'title_en' => [
                'type' => 'text',
            ],
            'status' => [
                'type' => 'text',
            ],
            'category' => 'nested',
            'features' => 'nested',
            'variants' => 'nested',
            'has_stock' => 'boolean',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title_fa' => $this->title_fa,
            'title_en' => $this->title_en,
            'status' => $this->status,
            'category' => $this->category,
            'features' => $this->productFeatures,
            'variants' => ProductVariantResource::collection($this->variants)->toArray(true),
            'has_stock' => $this->has_stock,
        ];
    }

    // public function indexSettings(): array
    // {
    //     return [
    //         "analysis" => [
    //             "analyzer" => [
    //                 "my_analyzer" => [
    //                     "type" => "custom",
    //                     "tokenizer" => "standard",
    //                     "filter" => ["lowercase", "my_filter"]
    //                 ]
    //             ],
    //             "filter" => [
    //                 "my_filter" => [
    //                     "type" => "ngram",
    //                     "min_gram" => 2,
    //                 ]
    //             ]
    //         ],
    //         "index" => [
    //             "max_ngram_diff" => 13
    //         ]
    //     ];
    // }


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

    public function orders()
    {
        return $this->hasMany(OrderShippingItem::class);
    }

    public function incredibles()
    {
        return $this->hasMany(IncredibleProduct::class);
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
        return $this->hasMany(ProductQuestion::class);
    }

    public function wishes()
    {
        return $this->belongsToMany(User::class, 'wishes');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function images()
    {
        return $this->hasMany(ProductGallery::class)->whereIn('mime_type', ["image/png", "image/jpeg", "image/jpg", "image/webp"]);
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

    public function largestVariant()
    {
        return $this->hasOne(ProductVariant::class)->ofMany('price', 'max');
    }





    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            // ->keepOriginalImageFormat()
            ->width(300)
            ->height(300)
            ->format(Manipulations::FORMAT_PNG);
    }


    /**
     * Get  grouped combinations
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function groupedCombinations(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => collect($this->combinations)->unique('variant_id')->groupBy('variant.group')->transform(function ($item, $key) {
                return ['group' => json_decode($key), 'values' => $item->transform(function ($combination, $key) {
                    $combination->variant->combination_id = $combination->id;
                    return $combination->variant;
                })];
            })->values()
        );
    }


    /**
     * Get  grouped features
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function groupedFeatures(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->productFeatures ? collect($this->productFeatures)->groupBy('feature.parent.title')->transform(function ($item, $key) {
                return ['feature' => $key, 'values' => $item->mapToGroups(function ($item) {
                    return [$item['feature']['title'] => $item['value']];
                })->transform(function ($xx, $uu) {
                    return ['title' => $uu, 'values' => $xx];
                })];
            })->all() : null
        );
    }

    /**
     * Calculate total stock.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->variants()->sum('stock')
        );
    }

    /**
     * check inventory of product variations
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function hasStock(): Attribute
    {
        return Attribute::make(
            // get: fn ($value) => $this->variants()->sum('stock') > 0 ? true : false,
            get: fn ($value) => true,
            // set: fn (string $value) =>  ['has_stock' => $value],
        );
    }


    /**
     * Get default delivery.
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
     * If the user has liked the product
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isWish(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->wishes()
                ->where('user_id', optional(request()->user())->id)
                ->exists(),
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

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('variants');
    }
}
