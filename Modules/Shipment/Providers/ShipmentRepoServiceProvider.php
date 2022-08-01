<?php

namespace Modules\Shipment\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Shipment\Repository\ShipmentRepository;
use Modules\Shipment\Repository\ShipmentRepositoryInterface;

class ShipmentRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(ShipmentRepositoryInterface::class, ShipmentRepository::class);
    }
}
