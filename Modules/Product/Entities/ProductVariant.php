<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipment\Entities\Shipment;
use Modules\Warranty\Entities\Warranty;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'warranty_id', 'price', 'discount', 'discount_price', 'stock',
        'weight', 'order_limit', 'default_on', 'shipment_id', 'discount_expire_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discount_expire_at' => 'datetime',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function incredible()
    {
        return $this->hasOne(IncredibleProduct::class, 'variant_id')->ofMany([
            'discount_expire_at' => 'max',
            'id' => 'max',
        ], function ($query) {
            $query->where('discount_expire_at', '>', now());
        });
    }

    public function combinations()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }

    /**
     * Calculate discount percent.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function calculateDiscount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->incredible ?  $this->incredible->discount : $this->discount,
        );
    }

    /**
     * Calculate discount percent.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function DiscountExpireDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->incredible ?  $this->incredible->discount_expire_at : $this->discount_expire_at,
        );
    }

    /**
     * Calculate discount price .
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function calculateDiscountPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->incredible ?  $this->price - $this->price * $this->incredible->discount / 100 : $this->price - $this->price * $this->discount / 100,
        );
    }

    /**
     * Calculate discount diff seconds .
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function calculateDiscountDiffSeconds(): Attribute
    {
        // return Attribute::make(
        //     get: fn ($value) => $this->incredible ?  $this->incredible->discount_expire_at->diffInSeconds(now()) :  $this->discount_expire_at->diffInSeconds(now()),
        // );

        return Attribute::make(
            get: function ($value) {
                if ($this->incredible) $this->incredible->discount_expire_at->diffInSeconds(now());
                $this->discount_expire_at ? $this->discount_expire_at->diffInSeconds(now()) : null;
            }
        );
    }

    /**
     * Calculate discount diff seconds .
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isPromotion(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->incredible || $this->discount_expire_at > now()
        );
    }
}
