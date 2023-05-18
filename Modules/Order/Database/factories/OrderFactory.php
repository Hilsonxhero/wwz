<?php

namespace Modules\Order\Database\factories;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cart\Entities\Cart;
use Modules\Order\Enums\OrderStatus;
use Modules\Payment\Entities\PaymentMethod;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->first()->id,
            'cart_id' => Cart::factory()->create()->first()->id,
            'payment_method_id' => PaymentMethod::factory()->create()->first()->id,
            'reference_code' => random_int(1000000, 10000000),
            'status' => OrderStatus::Accepted,
            'payment_remaining_time' => null,
            'returnable_until' => null,
            'remaining_amount' => 0,
            'payable_price' => fake()->randomDigit(),
            'is_returnable' => false,
            'price' => fake()->randomDigit(),
        ];
    }
}
