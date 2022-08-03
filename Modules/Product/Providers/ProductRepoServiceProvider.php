<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Product\Repository\FeatureRepository;
use Modules\Product\Repository\FeatureRepositoryInterface;
use Modules\Product\Repository\FeatureValueRepository;
use Modules\Product\Repository\FeatureValueRepositoryInterface;
use Modules\Product\Repository\ProductRepository;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\VariantGroupRepository;
use Modules\Product\Repository\VariantGroupRepositoryInterface;
use Modules\Product\Repository\VariantRepository;
use Modules\Product\Repository\VariantRepositoryInterface;

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
        $this->app->bind(VariantGroupRepositoryInterface::class, VariantGroupRepository::class);
        $this->app->bind(VariantRepositoryInterface::class, VariantRepository::class);
    }
}
