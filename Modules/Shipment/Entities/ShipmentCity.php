<?php

namespace Modules\Shipment\Entities;

use Modules\State\Entities\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentCity extends Model
{
    use HasFactory;

    protected $fillable = ['shipment_id', 'delivery_id', 'city_id'];


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function dates()
    {
        return $this->hasMany(ShipmentDate::class);
    }

    public function intervals()
    {
        return $this->hasManyThrough(ShipmentInterval::class, ShipmentDate::class);
    }

        /**
     * check intervals exists.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function hasIntervalScope(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !!$this->intervals()->exists(),
        );
    }

}
