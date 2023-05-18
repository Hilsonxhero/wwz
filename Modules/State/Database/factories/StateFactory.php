<?php

namespace Modules\State\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\State\Enums\StateStatus;

class StateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\State\Entities\State::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->words(rand(2, 5), true),
            'zone_code' => fake()->numberBetween(1000, 10000),
            'status' => StateStatus::ENABLE->value,
        ];
    }
}
