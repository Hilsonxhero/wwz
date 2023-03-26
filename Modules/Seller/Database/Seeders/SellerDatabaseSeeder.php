<?php

namespace Modules\Seller\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Seller\Database\Seeders\SellerTableSeeder;
use Modules\Seller\Entities\Seller;

class SellerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // $this->call("SellerTableSeeder");
        $this->call(SellerTableSeeder::class);
    }
}
