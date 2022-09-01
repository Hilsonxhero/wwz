<?php

namespace Modules\Article\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Article\Repository\ArticleRepository;
use Modules\Article\Repository\ArticleRepositoryInterface;

class ArticleRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }
}
