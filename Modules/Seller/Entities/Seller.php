<?php

namespace Modules\Seller\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\ProductVariant;
use Modules\State\Entities\City;
use Modules\State\Entities\State;

class Seller extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'state_id', 'city_id', 'name', 'lname', 'title', 'brand_name', 'code',
        'shaba_number', 'postal_code', 'job', 'national_identity_number', 'ip',
        'email', 'phone', 'password', 'about', 'website', 'telephone', 'status', 'birth', 'wallet', 'learning_status'
    ];

    protected static function newFactory()
    {
        return \Modules\Seller\Database\factories\SellerFactory::new();
    }


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
