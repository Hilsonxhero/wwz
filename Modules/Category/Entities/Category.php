<?php

namespace Modules\Category\Entities;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Image\Manipulations;
use Modules\Brand\Entities\Brand;
use Spatie\MediaLibrary\HasMedia;
use Modules\Banner\Entities\Banner;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\Product;
use Modules\Voucher\Entities\Voucher;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Hilsonxhero\ElasticVision\Application\Aliased;
use Hilsonxhero\ElasticVision\Application\Explored;
use Hilsonxhero\ElasticVision\Application\BePrepared;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Hilsonxhero\ElasticVision\Application\IndexSettings;

class Category extends Model implements HasMedia, Explored
// IndexSettings
{
    use HasFactory, SoftDeletes, Sluggable, InteractsWithMedia, Searchable;

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'link',
        'description',
        'parent_id',
        'status',
    ];

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'title_en' => [
                'type' => 'text',
                'analyzer' => 'keyword',
            ],
            'title' => [
                'type' => 'text',
                'analyzer' => 'keyword',
            ],
            'status' => [
                'type' => 'text',
                'analyzer' => 'keyword',
            ],
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_en' => $this->title_en,
            'status' => $this->status,

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
        return 'categories';
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
                'source' => 'title_en'
            ]
        ];
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
            ->format(Manipulations::FORMAT_PNG);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }



    public function banners()
    {
        return $this->morphMany(Banner::class, 'bannerable');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class)->whereNotNull('parent_id');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    /**
     * Get all of the vouchers.
     */
    public function vouchers()
    {
        return $this->morphToMany(Voucher::class, 'voucherable');
    }

    /**
     * Get sub parent
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function productsCount(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $categoryIds = $this->children()->pluck('id')->prepend($this->id);
                return Product::whereIn('category_id', $categoryIds)->count();
            }
        );
    }

    /**
     * Get sub parent
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function mainParent(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->parent ?   $this->parent->main_parent : $this
        );
    }
}
