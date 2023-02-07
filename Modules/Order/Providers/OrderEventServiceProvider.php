<?php

namespace Modules\Order\Providers;


use Modules\Order\Events\App\OrderCreated;
use Modules\Order\Listeners\App\ProcesseOrder;
use Modules\Voucher\Listeners\App\IncreaseVoucherUsed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
