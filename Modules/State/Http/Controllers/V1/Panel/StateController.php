<?php

namespace Modules\State\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\State\Repository\StateRepositoryInterface;
use Modules\State\Transformers\CityResource;
use Modules\State\Transformers\StateResource;

class StateController extends Controller
{
    private $stateRepo;

    public function __construct(StateRepositoryInterface $stateRepo)
    {
        $this->stateRepo = $stateRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $states = $this->stateRepo->all();
        return  StateResource::collection($states);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'name' => ['required'],
            'zone_code' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'zone_code' => $request->zone_code,
        ];

        $this->stateRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $state = $this->stateRepo->show($id);
        return new StateResource($state);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function cities($id)
    {
        $cities = $this->stateRepo->cities($id);
        // ApiService::_response($cities, 403);
        return  CityResource::collection($cities);
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
            'name' => ['required'],
            'zone_code' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'zone_code' => $request->zone_code,
        ];

        $this->stateRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $state = $this->stateRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
