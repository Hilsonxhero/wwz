<?php

namespace Modules\Order\Listeners\App;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Order\Notifications\v1\App\OrderConfirmationNotif;

class SendOrderNotif
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Notification::route('sms', null)
            ->notify(new OrderConfirmationNotif($event->order->user->phone, $event->order));
    }
}
