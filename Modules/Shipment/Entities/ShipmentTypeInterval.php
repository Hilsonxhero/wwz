<?php

namespace Modules\Shipment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentTypeInterval extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
        'order_capacity',
        'shipping_cost',
        'shipment_type_date_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
}
