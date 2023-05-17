<?php

namespace Modules\Product\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Product\Repository\PriceHistoryRepositoryInterface;

class SyncProductPriceHistory
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
        resolve(PriceHistoryRepositoryInterface::class)->create([
            'seller_id' => $event->variant->seller_id,
            'product_id' => $event->variant->product_id,
            'product_variant_id' => $event->variant->id,
            'price' => $event->variant->price,
            'discount_price' => $event->variant->calculate_total_discount_price,
        ]);
    }
}
