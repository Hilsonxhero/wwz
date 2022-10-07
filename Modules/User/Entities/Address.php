<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\State\Entities\City;
use Modules\State\Entities\State;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'state_id', 'city_id', 'address', 'postal_code', 'telephone',
        'mobile', 'is_default', 'latitude', 'longitude', 'building_number', 'unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
