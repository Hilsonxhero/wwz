<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;
use Modules\Seller\Entities\Seller;
use Modules\Shipment\Entities\Shipment;
use Modules\Warranty\Entities\Warranty;

class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->create()->first()->id,
            'seller_id' => Seller::factory()->create()->first()->id,
            'warranty_id' => Warranty::factory()->create()->first()->id,
            'shipment_id' => Shipment::factory()->create()->first()->id,
            'price' => fake()->randomDigit(),
            'discount' => fake()->numberBetween(1, 100),
            'discount_price' => fake()->randomDigit(),
            'stock' => fake()->numberBetween(1, 300),
            'weight' => fake()->numberBetween(100, 3000),
            'order_limit' => fake()->numberBetween(2, 8),
            'default_on' => true,
            'discount_expire_at' => null,

        ];
    }
}
