<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shipment\Repository\ShipmentCityRepositoryInterface;
use Modules\Shipment\Transformers\Panel\ShipmentCityResource;
use Modules\State\Repository\CityRepositoryInterface;

class ShipmentCityController extends Controller
{
    private $shipmentCityRepo;
    private $cityRepo;

    public function __construct(
        ShipmentCityRepositoryInterface $shipmentCityRepo,
        CityRepositoryInterface $cityRepo
    )
    {
        $this->shipmentCityRepo = $shipmentCityRepo;
        $this->cityRepo = $cityRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $city = $this->cityRepo->find($id);
        $items = $this->shipmentCityRepo->get($city);
        return ShipmentCityResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        ApiService::Validator($request->all(), [
            'city_id' => ['required'],
            'shipment_id' => ['required'],
            'delivery_id' => ['required'],
        ]);

        $data = [
            'city_id' => $request->input('city_id'),
            'shipment_id' => $request->input('shipment_id'),
            'delivery_id' => $request->input('delivery_id'),
        ];

        $this->shipmentCityRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($city, $id)
    {
        $shipment = $this->shipmentCityRepo->find($id);

        return new ShipmentCityResource($shipment);
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
            'city_id' => ['required'],
            'shipment_id' => ['required'],
            'delivery_id' => ['required'],
        ]);

        $data = [
            'city_id' => $request->input('city_id'),
            'shipment_id' => $request->input('shipment_id'),
            'delivery_id' => $request->input('delivery_id'),
        ];

        $this->shipmentCityRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($city, $id)
    {

        $this->shipmentCityRepo->delete($id);

        ApiService::_success(trans('response.responses.200'));
    }
}