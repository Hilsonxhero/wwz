<?php

namespace Modules\Page\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Page\Entities\Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->word(rand(4, 8), true),
            'title_en' => fake()->word(rand(4, 8), true),
            'content' => fake()->word(rand(4, 8), true),
        ];
    }
}
