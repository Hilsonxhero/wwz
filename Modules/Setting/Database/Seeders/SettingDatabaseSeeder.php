<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Repository\SettingRepositoryInterface;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = array(
            ['name' => "site_name", 'value' => json_encode('test')],
            ['name' => "site_description", 'value' => json_encode("test")],
            ['name' => "support_text", 'value' => json_encode("test")],
            ['name' => "email", 'value' => json_encode("info@wwz.com")],
            ['name' => "phone", 'value' => json_encode("021454323")],
            ['name' => "address", 'value' => json_encode("test")],
            ['name' => "copyright", 'value' => json_encode("test")],
            ['name' => "logo", 'value' => json_encode(asset('media/statics/logo.svg'))],
            ['name' => "links", 'value' => json_encode([
                ["title" => "خدمات مشتریان", 'values' => [
                    ["title" => "پاسخ به پرسش های متداول", "url" => "#"],
                    ["title" => "شرایط استفاده", "url" => "#"]
                ]],
                ["title" => "راهنمای خرید  ", 'values' => [
                    ["title" => "نحوه ثبت سفارش", "url" => "#"],
                    ["title" => "رویه ارسال سفارش", "url" => "#"],
                    ["title" => "شیوه های پرداخت", "url" => "#"]
                ]]
            ])],
        );

        // resolve(SettingRepositoryInterface::class)->insert($data);


        // $this->call("OthersTableSeeder");
    }
}
