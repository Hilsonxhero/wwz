<?php

namespace Modules\Warranty\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Warranty\Enums\WarrantyStatus;

class WarrantyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Warranty\Entities\Warranty::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->words(rand(2, 5), true),
            'status' => WarrantyStatus::ENABLE->value,
            'description' => fake()->words(rand(2, 5), true),
        ];
    }
}
