<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'phone', 'expired_at', 'ttl'];

    public $timestamps = false;
}
