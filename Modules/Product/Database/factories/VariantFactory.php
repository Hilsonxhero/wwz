<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\VariantGroup;

class VariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Variant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->word(rand(4, 8), true),
            'variant_group_id' =>  VariantGroup::factory()->create()->first()->id,
            'value' => fake()->word(rand(4, 8), true),
        ];
    }
}
