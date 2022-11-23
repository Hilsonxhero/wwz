<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    const DISABLE_STATUS = 'disable';
    const ENABLE_STATUS = 'enable';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';

    static $statuses = [self::DISABLE_STATUS, self::ENABLE_STATUS, self::PENDING_STATUS, self::REJECTED_STATUS];

    protected $fillable = [
        'title', 'slug', 'description', 'type', 'status', 'is_default',
    ];
}
