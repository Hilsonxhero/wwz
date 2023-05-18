<?php

namespace Modules\Payment\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payment\Enums\PaymentMethodStatus;
use Modules\Payment\Enums\PaymentStatus;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Payment\Entities\PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->words(rand(4, 8), true),
            'slug' => fake()->words(rand(4, 8), true),
            'description' => fake()->words(rand(4, 8), true),
            'type' => "online",
            'status' => PaymentMethodStatus::ENABLE->value,
            'is_default' => false,
        ];
    }
}
