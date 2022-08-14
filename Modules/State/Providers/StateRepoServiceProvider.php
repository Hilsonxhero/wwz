<?php

namespace Modules\State\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\State\Repository\CityRepository;
use Modules\State\Repository\CityRepositoryInterface;
use Modules\State\Repository\StateRepository;
use Modules\State\Repository\StateRepositoryInterface;

class StateRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
    }
}
