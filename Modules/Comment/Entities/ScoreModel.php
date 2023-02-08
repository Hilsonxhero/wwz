<?php

namespace Modules\Comment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Entities\Category;

class ScoreModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
