<?php

namespace Modules\State\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];


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
