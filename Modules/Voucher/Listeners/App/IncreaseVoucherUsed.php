<?php

namespace Modules\Voucher\Listeners\App;

use Modules\Voucher\Entities\Voucher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Voucher\Repository\VoucherRepositoryInterface;

class IncreaseVoucherUsed
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
        $user = auth()->user();
        $exists_voucher = $user->available_cart->config->voucher_id;
        if ($exists_voucher) {
            $voucher = resolve(VoucherRepositoryInterface::class)->find($exists_voucher);
            $voucher->increment('used');
        }
    }
}
