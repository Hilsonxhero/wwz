<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Product\Repository\FeatureRepository;
use Modules\Product\Repository\FeatureRepositoryInterface;
use Modules\Product\Repository\FeatureValueRepository;
use Modules\Product\Repository\FeatureValueRepositoryInterface;
use Modules\Product\Repository\ProductRepository;
use Modules\Product\Repository\ProductRepositoryInterface;

class ProductRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(FeatureValueRepositoryInterface::class, FeatureValueRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }
}
