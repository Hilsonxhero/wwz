<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecommendationProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
        'product_id',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }
}
