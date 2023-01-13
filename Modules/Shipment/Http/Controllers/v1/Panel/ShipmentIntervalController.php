<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shipment\Repository\ShipmentDateRepositoryInterface;
use Modules\Shipment\Repository\ShipmentIntervalRepositoryInterface;
use Modules\Shipment\Transformers\Panel\ShipmentIntervalResource;

class ShipmentIntervalController extends Controller
{

    private $intervalRepo;
    private $shipmentDateRepo;

    public function __construct(
        ShipmentIntervalRepositoryInterface $intervalRepo,
        ShipmentDateRepositoryInterface $shipmentDateRepo
    )
    {
        $this->intervalRepo = $intervalRepo;
        $this->shipmentDateRepo = $shipmentDateRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $shipment_date = $this->shipmentDateRepo->find($id);
        $intervals = $this->intervalRepo->get($shipment_date);
        return ShipmentIntervalResource::collection($intervals);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        ApiService::Validator($request->all(), [
            'start_at' => ['required'],
            'end_at' => ['required'],
            'order_capacity' => ['required'],
            'shipping_cost' => ['required'],
            'shipment_date_id' => ['required', 'exists:shipment_dates,id'],
        ]);

        $this->intervalRepo->create($request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($date, $id)
    {
        $interval = $this->intervalRepo->show($id);
        return new ShipmentIntervalResource($interval);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $date, $id)
    {
        ApiService::Validator($request->all(), [
            'start_at' => ['required'],
            'end_at' => ['required'],
            'order_capacity' => ['required'],
            'shipping_cost' => ['required'],
            'shipment_date_id' => ['required', 'exists:shipment_dates,id'],
        ]);

        $this->intervalRepo->update($id, $request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($date, $id)
    {
        $this->intervalRepo->delete($id);

        ApiService::_success(trans('response.responses.200'));
    }
}