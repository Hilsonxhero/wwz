<?php

namespace Modules\State\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'zone_code',
        'status',
    ];



    // protected static function newFactory()
    // {
    //     return \Modules\State\Database\factories\StateFactory::new();
    // }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
