<?php

namespace Modules\Shipment\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shipment\Entities\Delivery;

class ShipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Shipment\Entities\Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->words(rand(2, 5), true),
            'is_default' => true,
            'shipping_cost' => fake()->randomDigit(),
            'description' =>  fake()->words(rand(2, 5), true),
            'delivery_date' =>  fake()->words(rand(2, 5), true),
            'delivery_id' => Delivery::factory()->create()->first()->id,
        ];
    }
}
