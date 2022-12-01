<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Cart\Entities\CartShipping;
use Modules\Shipment\Entities\Shipment;

class OrderShipping extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'order_id',
        'date',
        'start_date',
        'end_date',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function items()
    {
        return $this->hasMany(OrderShippingItem::class);
    }
}
