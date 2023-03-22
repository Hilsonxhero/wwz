<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Cart\Entities\CartShipping;
use Modules\Shipment\Entities\Shipment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    /**
     * Calculate the total price of order items
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->items->sum(function ($item) {
                return $item->quantity * $item->price->selling_price;
            })
        );
    }
}
