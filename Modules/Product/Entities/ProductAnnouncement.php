<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id', 'user_id', 'type', 'via_sms', 'via_email',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
