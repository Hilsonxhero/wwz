<?php

namespace Modules\Comment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'score_model_id', 'comment_id', 'value',
    ];


    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function score_model()
    {
        return $this->belongsTo(ScoreModel::class);
    }
}
