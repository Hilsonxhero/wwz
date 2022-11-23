<?php

namespace Modules\Payment\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Category\Repository\CategoryBannerRepository;
use Modules\Category\Repository\CategoryBannerRepositoryInterface;
use Modules\Category\Repository\CategoryRepository;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Repository\CategorySlideRepository;
use Modules\Category\Repository\CategorySlideRepositoryInterface;
use Modules\Payment\Repository\GatewayRepository;
use Modules\Payment\Repository\GatewayRepositoryInterface;
use Modules\Payment\Repository\PaymentMethodRepository;
use Modules\Payment\Repository\PaymentMethodRepositoryInterface;

class PaymentRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
        $this->app->bind(GatewayRepositoryInterface::class, GatewayRepository::class);
    }
}
