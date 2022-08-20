<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'phone', 'expired_at'];

    public $timestamps = false;

//    protected static function newFactory()
//    {
//        return \Modules\User\Database\factories\SmsCodeFactory::new();
//    }
}
