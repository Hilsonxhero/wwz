<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Modules\Banner\Entities\Banner;
use Modules\Slide\Entities\Slide;

class Page extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title', 'title_en', 'slug', 'content',
    ];

    public function banners()
    {
        return $this->morphMany(Banner::class, 'bannerable');
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
