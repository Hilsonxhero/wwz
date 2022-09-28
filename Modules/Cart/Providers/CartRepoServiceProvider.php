<?php

namespace Modules\Cart\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Cart\Repository\CartItemRepository;
use Modules\Cart\Repository\CartItemRepositoryInterface;
use Modules\Cart\Repository\CartRepository;
use Modules\Cart\Repository\CartRepositoryInterface;

class CartRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(CartItemRepositoryInterface::class, CartItemRepository::class);
    }
}
