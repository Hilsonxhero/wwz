<?php

namespace Modules\Brand\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Brand\Entities\Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->words(rand(2, 5), true),
            'title_en' => fake()->words(rand(2, 5), true),
            'description' => fake()->words(rand(2, 5), true),
            'link' =>  fake()->words(rand(2, 5), true),
            // 'category_id' => null,
            'is_special' => false,
            'status' => "enable"
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'category_id' => $attributes['category_id'],
            ];
        });
    }
}
