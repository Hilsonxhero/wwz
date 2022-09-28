<?php

namespace Modules\User\Listeners\App;

use Modules\User\Entities\SmsCode;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Cart\Facades\Cart;

class StoreUserCart
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
        Cart::store($event->user->phone);
    }
}
