<?php

namespace Modules\Page\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Page\Repository\PageRepository;
use Modules\Page\Repository\PageRepositoryInterface;

class PageRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
    }
}
