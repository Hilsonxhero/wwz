<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shipment\Entities\ShipmentType;
use Modules\State\Repository\CityRepositoryInterface;
use Modules\Shipment\Transformers\Panel\ShipmentDateResource;
use Modules\Shipment\Repository\ShipmentCityRepositoryInterface;
use Modules\Shipment\Repository\ShipmentDateRepositoryInterface;
use Modules\Shipment\Repository\ShipmentTypeRepositoryInterface;

class ShipmentDateController extends Controller
{
    private $shipmentDateRepo;
    private $shipmentTypeRepo;
    private $cityRepo;
    private $shipmentCityRepo;

    public function __construct(
        ShipmentTypeRepositoryInterface $shipmentTypeRepo,
        ShipmentDateRepositoryInterface $shipmentDateRepo,
        ShipmentCityRepositoryInterface $shipmentCityRepo,
        CityRepositoryInterface $cityRepo
    ) {
        $this->shipmentDateRepo = $shipmentDateRepo;
        $this->shipmentTypeRepo = $shipmentTypeRepo;
        $this->cityRepo = $cityRepo;
        $this->shipmentCityRepo = $shipmentCityRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $shipment_type_city = $this->shipmentCityRepo->find($id);
        $dates = $this->shipmentCityRepo->dates($shipment_type_city);
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
            'shipment_type_city_id' => ['required', 'exists:shipment_type_cities,id'],
            'date' => ['required'],
            'is_holiday' => ['nullable'],
        ]);

        $date = createDatetimeFromFormat($request->input('date'), 'Y/m/d');
        $data = [
            'shipment_type_city_id' =>  $request->input('shipment_type_city_id'),
            'is_holiday' =>  $request->input('is_holiday'),
            'date' => $date,
        ];

        $this->shipmentDateRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($city, $id)
    {
        $type = $this->shipmentDateRepo->show($id);
        return new ShipmentDateResource($type);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $city, $id)
    {
        ApiService::Validator($request->all(), [
            'shipment_type_id' => ['required', 'exists:shipment_types,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'date' => ['required'],
            'is_holiday' => ['nullable'],
        ]);

        $this->shipmentDateRepo->update($id, $request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($city, $id)
    {
        $type = $this->shipmentDateRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
