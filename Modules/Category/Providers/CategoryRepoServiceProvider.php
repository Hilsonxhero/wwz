<?php

namespace Modules\Category\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Category\Repository\CategoryBannerRepository;
use Modules\Category\Repository\CategoryBannerRepositoryInterface;
use Modules\Category\Repository\CategoryRepository;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Repository\CategorySlideRepository;
use Modules\Category\Repository\CategorySlideRepositoryInterface;

class CategoryRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(CategorySlideRepositoryInterface::class, CategorySlideRepository::class);
        $this->app->bind(CategoryBannerRepositoryInterface::class, CategoryBannerRepository::class);
    }
}