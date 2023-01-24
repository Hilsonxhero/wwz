<?php

namespace Modules\Order\Entities;

use Illuminate\Support\Str;
use Modules\Cart\Entities\Cart;
use Modules\User\Entities\User;
use Modules\Payment\Entities\Payment;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\PaymentMethod;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Casts\OrderStatus;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'cart_id',
        'payment_method_id',
        'reference_code',
        'status',
        'payment_remaining_time',
        'returnable_until',
        'remaining_amount',
        'payable_price',
        'is_returnable',
        'price',
    ];

    protected $casts = [
        'price' => 'json',
        'order_status' => OrderStatus::class
    ];

    public static function booted()
    {
        static::saving(function ($order) {
            $order->reference_code = random_int(1000000, 10000000);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, "paymentable");
    }
    public function shippings()
    {
        return $this->hasMany(OrderShipping::class)->with('items');
    }


    /**
     * Calculate the order status fa
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function StatusFa(): Attribute
    {
        return Attribute::make(

            get: fn ($value) => $this->items->sum(function ($item) {
                return $item->quantity * json_decode($item->price)->selling_price;
            })
        );
    }
}
