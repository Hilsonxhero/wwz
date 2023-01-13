<?php

namespace Modules\Payment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Requests\Panel\GatewayRequest;
use Modules\Payment\Repository\GatewayRepositoryInterface;
use Modules\Payment\Transformers\Panel\GatewayResource;

class GatewayController extends Controller
{
    private $gatewayRepo;

    public function __construct(GatewayRepositoryInterface $gatewayRepo)
    {
        $this->gatewayRepo = $gatewayRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $methods = $this->gatewayRepo->all();
        return GatewayResource::collection($methods);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(GatewayRequest $request)
    {
        $data = array(
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'config' => $request->input('config'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'is_default' => $request->input('is_default'),
        );

        $method = $this->gatewayRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $method = $this->gatewayRepo->show($id);
        return new GatewayResource($method);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(GatewayRequest $request, $id)
    {
        $data = array(
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'config' => $request->input('config'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'is_default' => $request->input('is_default'),
        );

        $method = $this->gatewayRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $method = $this->gatewayRepo->delete($id);
        return new GatewayResource($method);
    }
}