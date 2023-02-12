<?php

namespace Modules\Comment\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function scores()
    {
        return $this->hasMany(CommentScore::class);
    }

    public function comments()
    {
        return $this->hasMany(CommentScore::class);
    }

    /**
     * Calculate discount percent.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->scores->average('value')
        );
    }
}
