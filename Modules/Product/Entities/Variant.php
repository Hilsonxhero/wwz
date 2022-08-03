<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_group_id', 'name', 'value',
    ];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\VariantFactory::new();
    }
}
