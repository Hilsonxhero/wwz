<?php

namespace Modules\Category\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Category\Repository\CategoryRepository;
use Modules\Category\Repository\CategoryRepositoryInterface;

class RepositoriesServiceProvider extends ServiceProvider
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
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }
}
