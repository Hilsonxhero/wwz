<?php

namespace Modules\Order\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Order\Repository\OrderRepository;
use Modules\Order\Repository\OrderRepositoryInterface;
use Modules\Order\Repository\OrderShippingRepository;
use Modules\Order\Repository\OrderShippingRepositoryInterface;

class OrderRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderShippingRepositoryInterface::class, OrderShippingRepository::class);
    }
}
