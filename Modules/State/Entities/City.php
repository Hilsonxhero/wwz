<?php

namespace Modules\State\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipment\Entities\ShipmentDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipment\Entities\ShipmentCity;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'zone_code',
        'code',
        'latitude',
        'longitude',
        'city_fast_sending',
        'pay_at_place',
        'status',
    ];

    public static function booted()
    {
        static::saving(function ($city) {
            $city->code = Str::random(7);
        });
    }

    protected static function newFactory()
    {
        return \Modules\State\Database\factories\CityFactory::new();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function shipments()
    {
        return $this->hasMany(ShipmentCity::class);
    }
}
