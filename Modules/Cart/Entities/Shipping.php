<?php

namespace Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentInterval;
use Modules\User\Entities\User;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cart_id',
        'shipment_id',
        'shipment_interval_id',
        'package_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function interval()
    {
        return $this->belongsTo(ShipmentInterval::class, 'shipment_interval_id');
    }

    public function cart_items()
    {
        return $this->hasMany(CartShipping::class);
    }
}
