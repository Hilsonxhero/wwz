<?php

namespace Modules\State\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\State\Entities\State;
use Modules\State\Enums\StateStatus;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\State\Entities\City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'state_id' => State::factory()->create()->first()->id,
            'name' => fake()->words(rand(2, 5), true),
            'zone_code' => fake()->numberBetween(1000, 10000),
            'latitude' => fake()->numberBetween(1000, 10000000),
            'longitude' => fake()->numberBetween(1000, 10000000),
            'city_fast_sending' => true,
            'pay_at_place' => true,
            'status' => StateStatus::ENABLE->value,
        ];
    }
}
