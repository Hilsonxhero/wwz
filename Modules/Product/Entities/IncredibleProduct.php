<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncredibleProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'variant_id', 'discount', 'discount_expire_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discount_expire_at' => 'datetime',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

//    protected static function newFactory()
//    {
//        return \Modules\Product\Database\factories\IncredibleProductFactory::new();
//    }
}
