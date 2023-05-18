<?php

namespace Modules\Banner\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Banner\Enum\BannerStatus;
use Modules\Page\Entities\Page;

class BannerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Banner\Entities\Banner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => fake()->word(rand(4, 8), true),
            'url' => fake()->word(rand(4, 8), true),
            'type' => "top",
            'position' => 1,
            'bannerable_id' => Page::factory()->create()->first()->id,
            'bannerable_type' => Page::class,
            'status' => BannerStatus::ENABLE->value,
        ];
    }
}
