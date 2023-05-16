<?php

namespace Modules\Category\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Category\Entities\Category::class;

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
            'parent_id' => null,
            'status' => "enable"
        ];
    }
}
