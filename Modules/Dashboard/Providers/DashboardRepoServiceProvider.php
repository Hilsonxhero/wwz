<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Dashboard\Repository\DashboardRepository;
use Modules\Dashboard\Repository\DashboardRepositoryInterface;

class DashboardRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }
}
