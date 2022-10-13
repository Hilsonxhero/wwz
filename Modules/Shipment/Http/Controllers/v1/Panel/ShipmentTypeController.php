<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shipment\Http\Requests\ShipmentTypeRequest;
use Modules\Shipment\Repository\ShipmentTypeRepositoryInterface;
use Modules\Shipment\Transformers\Panel\ShipmentTypeResource;

class ShipmentTypeController extends Controller
{
    private $shipmentRepo;

    public function __construct(ShipmentTypeRepositoryInterface $shipmentRepo)
    {
        $this->shipmentRepo = $shipmentRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $shipments = $this->shipmentRepo->all();
        return  ShipmentTypeResource::collection($shipments);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ShipmentTypeRequest $request)
    {

        $shipment = $this->shipmentRepo->create($request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $shipment = $this->shipmentRepo->show($id);

        return new ShipmentTypeResource($shipment);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $shipment = $this->shipmentRepo->update($id, $request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $shipment = $this->shipmentRepo->delete($id);

        ApiService::_success(trans('response.responses.200'));
    }
}
