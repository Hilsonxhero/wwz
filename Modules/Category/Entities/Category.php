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

class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Sluggable, InteractsWithMedia;

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
