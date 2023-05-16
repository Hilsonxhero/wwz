<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'feature_id', 'title', 'status',
    ];


    // protected static function newFactory()
    // {
    //     return \Modules\Product\Database\factories\FeatureValueFactory::new();
    // }


    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
