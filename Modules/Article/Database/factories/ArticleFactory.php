<?php

namespace Modules\Article\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Enums\ArticleStatus;
use Modules\Category\Entities\Category;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Article\Entities\Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory()->create()->first()->id,
            'title' => fake()->word(rand(4, 8), true),
            'content' => fake()->word(rand(4, 8), true),
            'description' => fake()->word(rand(4, 8), true),
            'status' => ArticleStatus::ENABLE->value,
            'published_at' => now(),
        ];
    }
}
