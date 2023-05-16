<?php

namespace Modules\Product\Database\factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title_fa' => fake()->words(rand(2, 5), true),
            'title_en' => fake()->words(rand(2, 5), true),
            'review' => fake()->words(rand(2, 5), true),
            'category_id' => fake()->words(rand(2, 5), true),
            'brand_id' => fake()->words(rand(2, 5), true),
            'status' => fake()->words(rand(2, 5), true),
            'delivery_id' => fake()->words(rand(2, 5), true),
        ];
    }
}
