<?php

namespace Modules\Product\Http\Controllers\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Feature;
use Modules\Product\Repository\FeatureRepositoryInterface;

class FeatureController extends Controller
{

    /**
     * @var FeatureRepositoryInterface
     */
    private $featureRepo;

    public function __construct(FeatureRepositoryInterface $featureRepo)
    {
        $this->featureRepo = $featureRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $features = $this->featureRepo->all();
        ApiService::_success($features);
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
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'parent_id' => $request->parent_id,
            'status' => $request->status,
        ];

        $this->featureRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $feature =  $this->featureRepo->find($id);
        ApiService::_success($feature);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function values($id)
    {
        $values =  $this->featureRepo->values($id);
        ApiService::_success($values);
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
            'status' => ['required', Rule::in(Feature::$statuses)],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'parent_id' => $request->parent_id,
            'status' => $request->status,
        ];

        $this->featureRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->featureRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
