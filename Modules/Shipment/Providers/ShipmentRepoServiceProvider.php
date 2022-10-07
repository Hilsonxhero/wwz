<?php

namespace Modules\Shipment\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Shipment\Repository\ShipmentDateRepository;
use Modules\Shipment\Repository\ShipmentDateRepositoryInterface;
use Modules\Shipment\Repository\ShipmentRepository;
use Modules\Shipment\Repository\ShipmentRepositoryInterface;
use Modules\Shipment\Repository\ShipmentTypeRepository;
use Modules\Shipment\Repository\ShipmentTypeRepositoryInterface;

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
        $this->app->bind(ShipmentTypeRepositoryInterface::class, ShipmentTypeRepository::class);
        $this->app->bind(ShipmentDateRepositoryInterface::class, ShipmentDateRepository::class);
    }
}
