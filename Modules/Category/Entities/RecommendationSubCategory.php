<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecommendationSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'main_category_id',
        'sub_category_id',
    ];

    public function main()
    {
        $this->belongsTo(Category::class, 'main_category_id');
    }
    public function sub()
    {
        $this->belongsTo(Category::class, 'sub_category_id');
    }
}
