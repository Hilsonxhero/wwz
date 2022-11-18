<?php

namespace Modules\Shipment\Entities;

use Modules\State\Entities\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentTypeCity extends Model
{
    use HasFactory;

    protected $fillable = ['shipment_type_id', 'delivery_type_id', 'city_id'];


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function delivery()
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id');
    }

    public function shipment()
    {
        return $this->belongsTo(ShipmentType::class, 'shipment_type_id');
    }

    public function dates()
    {
        return $this->hasMany(ShipmentTypeDate::class);
    }

    public function intervals()
    {
        return $this->hasManyThrough(ShipmentTypeInterval::class, ShipmentTypeDate::class);
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
