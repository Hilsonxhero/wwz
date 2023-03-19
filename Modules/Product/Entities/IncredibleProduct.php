<?php

namespace Modules\Product\Entities;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Product\Transformers\ProductResource;
use Hilsonxhero\ElasticVision\Application\Explored;
use Modules\Category\Transformers\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Transformers\App\IncredibleProductSearchEngineResource;
use Carbon\Carbon;

class IncredibleProduct extends Model implements Explored
{
    use HasFactory, Searchable;

    protected $fillable = [
        'product_id', 'variant_id', 'discount', 'discount_expire_at',
    ];

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'category' => 'nested',
            'discount' => 'keyword',
            'discount_diff_seconds' => 'keyword',
            'discount_expire_at' => 'date', // yyyy-MM-dd'T'HH:mm:ss
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->product->category),
            // 'product' => new IncredibleProductSearchEngineResource($this->product),
            'discount' => $this->discount,
            'discount_expire_at' => $this->discount_expire_at,
            'discount_diff_seconds' => $this->discount_diff_seconds

        ];
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'promotions';
    }


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discount_expire_at' => 'datetime',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get discount expire DiffInSeconds
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function discountDiffSeconds(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($this->discount_expire_at)->DiffInSeconds(now())
        );
    }

    //    protected static function newFactory()
    //    {
    //        return \Modules\Product\Database\factories\IncredibleProductFactory::new();
    //    }
}
