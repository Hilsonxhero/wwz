<?php

namespace Modules\Order\Listeners\App;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Product\Entities\ProductVariant;

class InventoryReduction
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
        foreach ($event->order_shipping->items as $key => $shipping) {
            ProductVariant::query()->where('id', $shipping->variant_id)->decrement('stock', $shipping->quantity);
        }
    }
}
