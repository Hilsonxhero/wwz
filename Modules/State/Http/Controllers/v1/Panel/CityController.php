<?php

namespace Modules\State\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\State\Entities\City;
use Modules\State\Repository\CityRepositoryInterface;
use Modules\State\Transformers\CityResource;

class CityController extends Controller
{
    private $cityRepo;

    public function __construct(CityRepositoryInterface $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $states = $this->cityRepo->all();
        return CityResource::collection($states);
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
            'state' => ['required', 'exists:states,id'],
            'status' => ['required']
        ]);

        $data = [
            'state_id' => $request->state,
            'name' => $request->name,
            'zone_code' => $request->zone_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city_fast_sending' => $request->city_fast_sending,
            'pay_at_place' => $request->pay_at_place,
            'status' => $request->status,
        ];

        $this->cityRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $state = $this->cityRepo->show($id);
        return new CityResource($state);
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
            'status' => ['required']
        ]);

        $data = [
            'name' => $request->name,
            'zone_code' => $request->zone_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city_fast_sending' => $request->city_fast_sending,
            'pay_at_place' => $request->pay_at_place,
            'status' => $request->status,
        ];
        $this->cityRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $state = $this->cityRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
