<?php

namespace Modules\Shipment\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryType extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'code'];



    public static function booted()
    {
        static::saving(function ($item) {
            $item->code = random_int(1000, 10000);
        });
    }


    // protected static function newFactory()
    // {
    //     return \Modules\Shipment\Database\factories\DeliveryTypeFactory::new();
    // }
}
