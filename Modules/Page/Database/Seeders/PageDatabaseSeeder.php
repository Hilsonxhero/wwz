<?php

namespace Modules\Page\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Page\Repository\PageRepositoryInterface;

class PageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        resolve(PageRepositoryInterface::class)->create([
            'title' => "صفحه اصلی",
            'title_en' => "landing",
            'content' => "",
        ]);


        // $this->call("OthersTableSeeder");
    }
}
