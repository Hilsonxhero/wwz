<?php

namespace Modules\Order\Providers;


use Modules\Order\Events\App\OrderCreated;
use Modules\Order\Listeners\App\ProcesseOrder;
use Modules\Order\Listeners\App\InventoryReduction;
use Modules\Voucher\Listeners\App\IncreaseVoucherUsed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Order\Listeners\App\SendOrderNotif;

class OrderEventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        OrderCreated::class => [
            IncreaseVoucherUsed::class,
            ProcesseOrder::class,
            InventoryReduction::class,
            SendOrderNotif::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
