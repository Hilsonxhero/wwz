<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductFeatureRepositoryInterface;
use Modules\Product\Transformers\ProductFeatureResource;


class ProductFeatureController extends Controller
{

    private $featureRepo;
    private $productRepo;

    public function __construct(
        ProductFeatureRepositoryInterface $featureRepo,
        ProductRepositoryInterface $productRepo
    )
    {
        $this->featureRepo = $featureRepo;
        $this->productRepo = $productRepo;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($id)
    {
        // $features = $this->featureRepo->show($id);
        $features = $this->productRepo->find($id)->productFeatures()->paginate();
        return ProductFeatureResource::collection($features);
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
            'product_id' => ['required', 'exists:products,id'],
            'feature_id' => ['required', 'exists:features,id'],
            'feature_value_id' => ['nullable', 'exists:feature_values,id'],
            'value' => ['nullable', 'min:3']
        ]);

        $data = [
            'product_id' => $request->product_id,
            'feature_id' => $request->feature_id,
            'feature_value_id' => $request->feature_value_id,
            'value' => $request->value,
        ];

        $feature = $this->featureRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id, $feature)
    {
        $p_feature = $this->featureRepo->find($feature);
        return new ProductFeatureResource($p_feature);
        // ApiService::_success($p_feature);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id, $feature)
    {

        ApiService::Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'feature_id' => ['required', 'exists:features,id'],
            'feature_value_id' => ['nullable', 'exists:feature_values,id'],
            'value' => ['nullable', 'min:3']
        ]);

        $data = [
            'product_id' => $request->product_id,
            'feature_id' => $request->feature_id,
            'feature_value_id' => $request->feature_value_id,
            'value' => $request->value,
        ];

        $feature = $this->featureRepo->update($feature, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id, $feature)
    {
        $feature = $this->featureRepo->delete($feature);
        ApiService::_success(trans('response.responses.200'));
    }
}