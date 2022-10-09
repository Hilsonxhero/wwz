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
use Modules\State\Repository\CityRepositoryInterface;

class ShipmentDateController extends Controller
{
    private $shipmentDateRepo;
    private $shipmentTypeRepo;
    private $cityRepo;

    public function __construct(
        ShipmentTypeRepositoryInterface $shipmentTypeRepo,
        ShipmentDateRepositoryInterface $shipmentDateRepo,
        CityRepositoryInterface $cityRepo
    ) {
        $this->shipmentDateRepo = $shipmentDateRepo;
        $this->shipmentTypeRepo = $shipmentTypeRepo;
        $this->cityRepo = $cityRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        // $shipment = $this->shipmentTypeRepo->find($id);
        $city = $this->cityRepo->find($id);
        $dates = $this->shipmentDateRepo->get($city);
        return  ShipmentDateResource::collection($dates);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'shipment_type_id' => ['required', 'exists:shipment_types,id'],
            'city_id' => ['required', 'exists:cities,id'],
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
