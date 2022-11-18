<?php

namespace Modules\Shipment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_city_id',
        'date',
        'is_holiday',
    ];


    public function shipment()
    {
        return $this->belongsTo(ShipmentCity::class, 'shipment_city_id');
    }

    public function intervals()
    {
        return $this->hasMany(ShipmentInterval::class);
    }

    /**
     * check intervals exists.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function hasTimeScope(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !!$this->intervals()->exists(),
        );
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];
}
