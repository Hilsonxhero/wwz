<?php

namespace Modules\Seller\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Seller\Entities\Seller;
use Illuminate\Database\Eloquent\Model;
use Modules\State\Database\Seeders\StateTableSeeder;

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
        $this->call(StateTableSeeder::class);
        Seller::factory(1)->create();
    }
}
