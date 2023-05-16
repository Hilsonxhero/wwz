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




    // protected static function newFactory()
    // {
    //     return \Modules\Product\Database\factories\FeatureFactory::new();
    // }



    public function values()
    {
        return $this->hasMany(FeatureValue::class);
    }

    public function childs()
    {
        return $this->hasMany(Feature::class, 'parent_id');
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
