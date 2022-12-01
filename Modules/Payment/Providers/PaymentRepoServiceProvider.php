<?php

namespace Modules\Payment\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Modules\Payment\Repository\GatewayRepository;
use Modules\Payment\Repository\GatewayRepositoryInterface;
use Modules\Payment\Repository\PaymentMethodRepository;
use Modules\Payment\Repository\PaymentMethodRepositoryInterface;
use Modules\Payment\Repository\PaymentRepository;
use Modules\Payment\Repository\PaymentRepositoryInterface;

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
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }
}
