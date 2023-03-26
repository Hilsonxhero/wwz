<?php

namespace Modules\Seller\Database\factories;

use Illuminate\Support\Str;
use Modules\State\Entities\City;
use Modules\State\Entities\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Seller\Entities\Seller::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'state_id' => State::query()->first()->id,
            'city_id' => City::query()->first()->id,
            'name' => "لورم ایپسوم ",
            'lname' => "لورم ",
            'title' => "لورم ایپسوم ",
            'brand_name' => "لورم ایپسوم ",
            'code' =>  Str::random(8),
            'shaba_number' => Str::random(19),
            'postal_code' => Str::random(8),
            'job' => "لورم ایپسوم ",
            'national_identity_number' => Str::random(10),
            'email' => "info@web.com",
            'phone' => Str::random(11),
            'about' => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم ",
            'website' => null,
            'telephone' => null,
            'status' => "pending",
            'wallet' => 0,
            'is_default' => true,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
}
