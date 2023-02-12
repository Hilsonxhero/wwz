<?php

namespace Modules\Product\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductQuestion extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id', 'product_question_id', 'questionable_id', 'questionable_type',
        'content', 'like', 'dislike', 'report', 'status', 'is_anonymous',
        'is_buyer', 'is_recommendation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quantion()
    {
        return $this->belongsTo(ProductQuestion::class);
    }

    public function replies()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function questionable()
    {
        return $this->morphTo();
    }
}
