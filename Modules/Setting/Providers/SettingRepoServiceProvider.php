<?php

namespace Modules\Setting\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Setting\Repository\SettingBannerRepository;
use Modules\Setting\Repository\SettingBannerRepositoryInterface;
use Modules\Setting\Repository\SettingRepository;
use Modules\Setting\Repository\SettingRepositoryInterface;

class SettingRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(SettingBannerRepositoryInterface::class, SettingBannerRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
    }
}
