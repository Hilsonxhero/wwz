<?php

namespace Modules\State\Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class StateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // $states = array(
        //     ['name' => 'فارس', 'zone_code' => Str::rand(999, 99999), 'status' => 'enable', 'cities' => array([
        //         'name' => "شیراز",
        //         'zone_code' => Str::rand(999, 99999),
        //         'code' => Str::rand(999, 99999),
        //         'longitude' => "341212",
        //         'latitude' => "223.344434",
        //         'city_fast_sending' => false,
        //         'pay_at_place' => false,
        //         'status' => 'enable'
        //     ])],
        //     ['name' => 'تهرات', 'zone_code' => Str::rand(999, 99999), 'status' => 'enable', 'cities' => array([
        //         'name' => "تهرات",
        //         'zone_code' => Str::rand(999, 99999),
        //         'code' => Str::rand(999, 99999),
        //         'longitude' => "341212",
        //         'latitude' => "223.344434",
        //         'city_fast_sending' => false,
        //         'pay_at_place' => false,
        //         'status' => 'enable'
        //     ])]
        // );

        // foreach ($states as $key => $state) {
        //     $create_state = resolve(StateRepositoryInterface::class)->create([
        //         'name' => $state['name'],
        //         'zone_code' => $state['zone_code'],
        //         'status' => $state['status'],
        //     ]);
        //     resolve(StateRepositoryInterface::class)->createManyCity($create_state, $state['cities']);
        // }
    }
}
