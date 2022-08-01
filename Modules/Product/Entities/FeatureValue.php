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

    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\FeatureValueFactory::new();
    }


    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
