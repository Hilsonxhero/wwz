<?php

namespace Modules\Cart\Database\factories;

use Illuminate\Support\Str;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cart\Enums\CartStatus;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Cart\Entities\Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->first()->id,
            'identifier' => Str::random(8),
            'instance' => Str::random(8),
            'address' => fake()->words(rand(4, 8), true),
            'config' => null,
            'status' => CartStatus::Available->value,
        ];
    }
}
