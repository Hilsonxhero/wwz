<?php

namespace Modules\Brand\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Entities\Category;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Brand extends Model implements HasMedia
{
    use HasFactory, Sluggable, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'title_en', 'slug', 'link', 'description', 'category_id', 'status', 'is_special',
    ];

    protected static function newFactory()
    {
        return \Modules\Brand\Database\factories\BrandFactory::new();
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    public static function last()
    {
        return static::all()->last();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232);
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
}
