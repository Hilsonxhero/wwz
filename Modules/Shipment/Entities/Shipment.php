<?php

namespace Modules\Shipment\Entities;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipment\Entities\Delivery;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipment\Database\factories\ShipmentFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Shipment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'delivery_id',
        'delivery_date',
        'title',
        'description',
        'shipping_cost',
        'is_default'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

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
        return $this->hasMany(ShipmentDate::class, 'shipment_id');
    }

    protected static function newFactory()
    {
        return ShipmentFactory::new();
    }
}
