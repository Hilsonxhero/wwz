<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Enums\CommentStatus;
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

        // Order::factory(10000)->create([
        //     'user_id' => 1,
        //     'cart_id' => 1,
        //     'payment_method_id' => 1,
        //     'reference_code' => "343434",
        //     'status' => "sent",
        //     'payable_price' => random_int(1000, 10000000000),
        //     'is_returnable' => 0,
        //     'price' => "{\"payable_price\":105000000,\"rrp_price\":105000000,\"shipment_cost\":0}",
        // ]);
        // Comment::factory(100000)->create([
        //     'user_id' => 1,
        //     'commentable_id' => 4,
        //     'commentable_type' => "Modules\Product\Entities\Product",
        //     'title' => "sdfsdfsdfsdfsd",
        //     'body' => "sfdsddddddddddddddddddd",
        //     'advantages' => "[]",
        //     'disadvantages' => "[]",
        //     "is_buyer" => 0,
        //     "is_anonymous" => 0,
        //     "is_recommendation" => 0,
        //     "like" => 0,
        //     "dislike" => 0,
        //     "report" => 0,
        //     'status' => CommentStatus::Approved->value,
        // ]);
    }
}
