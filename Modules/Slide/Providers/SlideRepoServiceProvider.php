<?php

namespace Modules\Slide\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Slide\Repository\SlideRepository;
use Modules\Slide\Repository\SlideRepositoryInterface;

class SlideRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(SlideRepositoryInterface::class, SlideRepository::class);
    }
}
