<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\FeatureValue;
use Modules\Product\Repository\FeatureValueRepositoryInterface;

class FeatureValueController extends Controller
{

    /**
     * @var FeatureValueRepositoryInterface
     */
    private $featureValueRepo;

    public function __construct(FeatureValueRepositoryInterface $featureValueRepo)
    {
        $this->featureValueRepo = $featureValueRepo;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $values = $this->featureValueRepo->all();
        ApiService::_success($values);
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
            'feature_id' => ['required', 'exists:features,id'],
        ]);

        $data = [
            'title' => $request->title,
            'feature_id' => $request->feature_id,
            'status' => $request->status,
        ];

        $this->featureValueRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $feature =  $this->featureValueRepo->find($id);
        ApiService::_success($feature);
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
            'feature_id' => ['required', 'exists:features,id'],
            'status' => ['required', Rule::in(FeatureValue::$statuses)]
        ]);

        $data = [
            'title' => $request->title,
            'feature_id' => $request->feature_id,
            'status' => $request->status,
        ];

        $this->featureValueRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->featureValueRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
