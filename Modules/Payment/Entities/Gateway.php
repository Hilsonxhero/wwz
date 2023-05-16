<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gateway extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    const SMS_TYPE = 'sms';
    const BANK_TYPE = 'bank';

    static $types = [self::SMS_TYPE, self::BANK_TYPE];

    protected $fillable = [
        'title', 'slug', 'config', 'is_default', 'type', 'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'json',
    ];

    public static function last()
    {
        return static::all()->last();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300);
    }
}
