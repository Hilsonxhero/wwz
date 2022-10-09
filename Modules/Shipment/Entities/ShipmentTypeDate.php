<?php

namespace Modules\Shipment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentTypeDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_type_id',
        'city_id',
        'date',
        'is_holiday',
    ];


    public function shipment_type()
    {
        return $this->belongsTo(ShipmentType::class);
    }

    public function intervals()
    {
        return $this->hasMany(ShipmentTypeInterval::class);
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
