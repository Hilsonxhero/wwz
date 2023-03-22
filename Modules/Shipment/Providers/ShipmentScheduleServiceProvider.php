<?php

namespace Modules\Shipment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Shipment\Jobs\GenerateShipmentDate;

class ShipmentScheduleServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            // $schedule = $this->app->make(Schedule::class);
            // $schedule->job(new GenerateShipmentDate)->everyMinute();
        });
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
