<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Entities\Category;

class Feature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'category_id', 'parent_id', 'position', 'status',
    ];


    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\FeatureFactory::new();
    }


    public function values()
    {
        return $this->hasMany(FeatureValue::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function parent()
    {
        return $this->belongsTo(Feature::class, 'parent_id');
    }
}
