<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipment\Entities\Shipment;
use Modules\Warranty\Entities\Warranty;

class ProductVariant extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'product_id', 'warranty_id', 'price', 'discount', 'discount_price', 'stock',
        'weight', 'order_limit', 'default_on', 'shipment_id','discount_expire_at'
    ];

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
    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function combinations()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }


    function insertOrUpdate(array $rows){
        $table = \DB::getTablePrefix().with(new self)->getTable();


        $first = reset($rows);

        $columns = implode( ',',
            array_map( function( $value ) { return "$value"; } , array_keys($first) )
        );

        $values = implode( ',', array_map( function( $row ) {
                return '('.implode( ',',
                        array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                    ).')';
            } , $rows )
        );

        $updates = implode( ',',
            array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
        );

        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return \DB::statement( $sql );
    }

}
