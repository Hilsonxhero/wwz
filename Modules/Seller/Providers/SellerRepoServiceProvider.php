<?php

namespace Modules\Seller\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Seller\Repository\SellerRepository;
use Modules\Seller\Repository\SellerRepositoryInterface;

class SellerRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
    }
}
