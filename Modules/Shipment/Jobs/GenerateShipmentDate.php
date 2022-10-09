<?php

namespace Modules\Shipment\Jobs;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Shipment\Repository\ShipmentTypeRepository;

class GenerateShipmentDate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = Carbon::now();

        $period = Carbon::now()->addWeek(1);

        $days = CarbonPeriod::create($today, $period)->toArray();

        $week_days = array();

        foreach ($days as $oneDay) {
            $date = $oneDay->format('Y/m/d');
            $day = $oneDay->format('D');

            array_push($week_days, [
                'date' => $date,
                'weekday_name' => formatGregorian($day, '%A'),
                'is_holiday' => 0
            ]);
        }

        $shippings = resolve(ShipmentTypeRepository::class)->get();

        foreach ($shippings as $key => $shipping) {
            resolve(ShipmentTypeRepository::class)->createMany($shipping, $week_days);
        }
    }
}
