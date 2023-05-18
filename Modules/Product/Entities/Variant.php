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

    public $with = ['group'];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\VariantFactory::new();
    }

    public function group()
    {
        return $this->belongsTo(VariantGroup::class, 'variant_group_id');
    }
}
