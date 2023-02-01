<?php

namespace Modules\Voucher\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Voucher\Repository\VoucherableRepository;
use Modules\Voucher\Repository\VoucherableRepositoryInterface;
use Modules\Voucher\Repository\VoucherRepository;
use Modules\Voucher\Repository\VoucherRepositoryInterface;

class VoucherRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(VoucherRepositoryInterface::class, VoucherRepository::class);
        $this->app->bind(VoucherableRepositoryInterface::class, VoucherableRepository::class);
    }
}
