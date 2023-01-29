<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Entities\Category;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(RecommendationProduct::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
