<?php

namespace Modules\Brand\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Brand\Repository\BrandRepository;
use Modules\Brand\Repository\BrandRepositoryInterface;
use Modules\Category\Repository\CategoryRepository;
use Modules\Category\Repository\CategoryRepositoryInterface;

class BrandRepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
    }
}
