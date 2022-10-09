<?php

namespace Modules\State\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipment\Entities\ShipmentTypeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


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



    // protected static function newFactory()
    // {
    //     return \Modules\State\Database\factories\CityFactory::new();
    // }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function dates()
    {
        return $this->hasMany(ShipmentTypeDate::class);
    }
}
