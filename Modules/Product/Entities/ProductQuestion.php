<?php

namespace Modules\Product\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductQuestion extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'user_id', 'product_question_id', 'product_id',
        'content', 'like', 'dislike', 'report', 'status', 'is_anonymous',
        'is_buyer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function quantion()
    {
        return $this->belongsTo(ProductQuestion::class);
    }

    public function replies()
    {
        return $this->hasMany(ProductQuestion::class);
    }
}
