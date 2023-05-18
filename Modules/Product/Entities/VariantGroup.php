<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'order',
    ];

    const CHECKBOX_TYPE = 'checkbox';
    const COLOR_TYPE = 'color';
    const SELECT_TYPE = 'select';
    const SIZE_TYPE = 'size';

    static $types = [self::CHECKBOX_TYPE, self::COLOR_TYPE, self::SELECT_TYPE, self::SIZE_TYPE];


    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\VariantGroupFactory::new();
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
