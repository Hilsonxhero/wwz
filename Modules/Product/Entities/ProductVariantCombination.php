<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariantCombination  extends Pivot
{
    use HasFactory;

    protected $table = 'product_variant_combination';

    public $timestamps = false;

    public $with = ['variant'];

    protected $fillable = ['variant_id', 'product_variant_id'];


    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
