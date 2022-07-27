<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'link',
        'description',
        'parent_id',
        'status',
    ];


    //INFO:enum value
    // protected $casts = [
    //     'status' =>  \Modules\Category\Enum\CategoryEnum::class
    // ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\CategoryFactory::new();
    }


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
                'source' => 'title'
            ]
        ];
    }
}
