<?php

namespace Modules\State\Http\Controllers\v1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\State\Transformers\CityResource;
use Modules\State\Transformers\StateResource;
use Modules\State\Repository\StateRepositoryInterface;

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
        $states = $this->stateRepo->get();
        return StateResource::collection($states);
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
        return CityResource::collection($cities);
    }
}