<?php

namespace Modules\Voucher\Providers;

use Modules\Voucher\Events\App\VoucherProcessed;
use Modules\Voucher\Listeners\App\IncreaseVoucherUsed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class VoucherEventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // VoucherProcessed::class => [
        //     IncreaseVoucherUsed::class,
        // ],
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
