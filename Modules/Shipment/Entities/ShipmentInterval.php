<?php

namespace Modules\Shipment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentInterval extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
        'order_capacity',
        'shipping_cost',
        'shipment_date_id',
    ];



    public function shipment_date()
    {
        return $this->belongsTo(ShipmentDate::class, 'shipment_date_id');
    }
}
