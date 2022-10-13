<?php

namespace Modules\Shipment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ShipmentType extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'shipping_cost',
        'is_default'
    ];

    public static function last()
    {
        return static::all()->last();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232);
    }

    public function dates()
    {
        return $this->hasMany(ShipmentTypeDate::class, 'shipment_type_id');
    }
}
