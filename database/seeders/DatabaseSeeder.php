<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Order\Entities\Order;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Order::factory(10000)->create([
            'user_id' => 1,
            'cart_id' => 1,
            'payment_method_id' => 1,
            'reference_code' => "343434",
            'status' => "sent",
            'payable_price' => random_int(1000, 10000000000),
            'is_returnable' => 0,
            'price' => "{\"payable_price\":105000000,\"rrp_price\":105000000,\"shipment_cost\":0}",
        ]);
    }
}
