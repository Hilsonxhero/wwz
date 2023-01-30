<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\Panel\RecommendationRequest;
use Modules\Product\Repository\RecommendationProductRepoInterface;
use Modules\Product\Repository\RecommendationRepoInterface;
use Modules\Product\Transformers\Panel\RecommendationProductResource;
use Modules\Product\Transformers\Panel\RecommendationResource;
use Modules\Product\Transformers\Panel\RecommendationSelectResource;

class RecommendationController extends Controller
{
    private $recommendationRepo;

    public function __construct(RecommendationRepoInterface $recommendationRepo)
    {
        $this->recommendationRepo = $recommendationRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $recommendations = $this->recommendationRepo->all();
        return RecommendationResource::collection($recommendations);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function select()
    {
        $recommendations = $this->recommendationRepo->select();
        return RecommendationSelectResource::collection($recommendations);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(RecommendationRequest $request)
    {
        $data = array(
            'category_id' => $request->category_id,
            'description' => $request->description,
        );
        $this->recommendationRepo->create($data);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $recommendation = $this->recommendationRepo->show($id);
        return new RecommendationResource($recommendation);
    }

    /**
     * Show the recommendation products.
     * @param int $id
     * @return Response
     */
    public function products($id)
    {

        $products = $this->recommendationRepo->products($id);
        return  RecommendationProductResource::collection($products);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(RecommendationRequest $request, $id)
    {
        $data = array(
            'category_id' => $request->category_id,
            'description' => $request->description,
        );
        $this->recommendationRepo->update($id, $data);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $recommendation = $this->recommendationRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
