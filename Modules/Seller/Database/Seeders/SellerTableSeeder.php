<?php

namespace Modules\Seller\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Seller\Entities\Seller;
use Illuminate\Database\Eloquent\Model;

class SellerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Seller::factory(1)->create();

        // $this->call("OthersTableSeeder");
    }
}
