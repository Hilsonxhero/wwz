<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Setting\Traits\SettingTrait;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Setting extends Model implements HasMedia
{
    use HasFactory, SettingTrait, InteractsWithMedia;

    protected $fillable = ['name', 'value'];

    public $timestamps = false;


    public static function last()
    {
        return static::all()->last();
    }

    protected $casts = [
        'value' => 'json'
    ];
}
