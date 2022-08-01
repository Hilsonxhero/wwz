<?php

namespace Modules\Warranty\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Warranty\Repository\WarrantyRepository;
use Modules\Warranty\Repository\WarrantyRepositoryInterface;

class WarrantyRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(WarrantyRepositoryInterface::class, WarrantyRepository::class);
    }
}
