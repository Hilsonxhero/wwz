<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shipment\Entities\ShipmentType;
use Modules\Shipment\Transformers\Panel\ShipmentDateResource;
use Modules\Shipment\Repository\ShipmentDateRepositoryInterface;
use Modules\Shipment\Repository\ShipmentTypeRepositoryInterface;

class ShipmentDateController extends Controller
{
    private $shipmentDateRepo;
    private $shipmentTypeRepo;

    public function __construct(ShipmentTypeRepositoryInterface $shipmentTypeRepo, ShipmentDateRepositoryInterface $shipmentDateRepo)
    {
        $this->shipmentDateRepo = $shipmentDateRepo;
        $this->shipmentTypeRepo = $shipmentTypeRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $shipment = $this->shipmentTypeRepo->find($id);
        $dates = $this->shipmentDateRepo->get($shipment);
        return new ShipmentDateResource($dates);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $today = Carbon::now();

        $today2 = Carbon::now();

        $next_two_week = $today->addWeek(1);

        $days = CarbonPeriod::create($today2, $next_two_week)->toArray();

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

        $shippings = ShipmentType::query()->get();

        foreach ($shippings as $key => $shipping) {
            $shipping->dates()->createMany($week_days);
        }

        ApiService::_success($week_days);

        ApiService::Validator($request->all(), [
            'shipment_type_id' => ['required'],
            'date' => ['required'],
            'is_holiday' => ['nullable'],
        ]);

        $this->shipmentDateRepo->create($request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
