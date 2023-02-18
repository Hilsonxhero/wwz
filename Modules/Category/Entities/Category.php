<?php

namespace Modules\Category\Entities;

use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Modules\Slide\Entities\Slide;
use Spatie\MediaLibrary\HasMedia;
use Modules\Banner\Entities\Banner;
use Modules\Product\Entities\Product;
use Modules\Voucher\Entities\Voucher;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Scout\Searchable;
use Hilsonxhero\ElasticVision\Application\Explored;


class Category extends Model implements HasMedia, Explored
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



    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'title_en' => [
                'type' => 'text',
                // 'analyzer' => 'synonym',
            ],
            'title' => [
                'type' => 'text',
                'analyzer' => 'whitespace',
            ],
            'status' => [
                'type' => 'text',
                // 'analyzer' => 'synonym',
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
        return $this->hasMany(Category::class, 'parent_id')->with('children')->withCount(['products']);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function slides()
    {
        return $this->morphMany(Slide::class, 'slideable');
    }

    public function banners()
    {
        return $this->morphMany(Banner::class, 'bannerable');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the vouchers.
     */
    public function vouchers()
    {
        return $this->morphToMany(Voucher::class, 'voucherable');
    }
}
