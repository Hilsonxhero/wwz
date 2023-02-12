<?php

namespace Modules\Comment\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Comment\Casts\CommentableTitle;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'comment_id', 'commentable_id', 'commentable_type', 'title',
        'body', 'like', 'dislike', 'report', 'status', 'is_anonymous',
        'is_buyer', 'is_recommendation', 'advantages', 'disadvantages',
    ];

    protected $casts = [
        'commentable_title' => CommentableTitle::class,
        'advantages' => 'json',
        'disadvantages' => 'json'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class);
    }

    public function scores()
    {
        return $this->hasMany(CommentScore::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }



    /**
     * Calculate discount percent.
     *
     * @return Attribute
     */
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->scores->average('value')
        );
    }
}
