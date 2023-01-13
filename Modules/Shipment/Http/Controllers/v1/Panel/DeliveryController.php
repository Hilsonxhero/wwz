<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shipment\Repository\DeliveryRepositoryInterface;
use Modules\Shipment\Transformers\Panel\DeliveryResource;

class DeliveryController extends Controller
{

    private $deliveryRepo;

    public function __construct(DeliveryRepositoryInterface $deliveryRepo)
    {
        $this->deliveryRepo = $deliveryRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $types = $this->deliveryRepo->all();
        return DeliveryResource::collection($types);
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function select()
    {
        $types = $this->deliveryRepo->get();
        return DeliveryResource::collection($types);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'title' => ['required'],
        ]);

        $this->deliveryRepo->create($request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $type = $this->deliveryRepo->show($id);
        return new DeliveryResource($type);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        ApiService::Validator($request->all(), [
            'title' => ['required'],
        ]);

        $this->deliveryRepo->update($id, $request);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->deliveryRepo->delete($id);

        ApiService::_success(trans('response.responses.200'));
    }
}