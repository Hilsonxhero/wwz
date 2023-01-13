<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\ProductVariantCombinationResource;

class ProductController extends Controller
{

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }


    public function index(Request $request)
    {
        $products = $this->productRepo->all($request->q);
        return ProductResource::collection($products);
    }


    public function select(Request $request)
    {
        $products = $this->productRepo->select($request->q);
        ApiService::_success($products);
    }


    public function combinations($id)
    {
        //        ApiService::_response("www",403);
        $combinations = $this->productRepo->combinations($id);
        return ProductVariantCombinationResource::collection($combinations);
        //        ApiService::_success($combinations);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productRepo->create($request);
        ApiService::_success($product);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return ProductResource
     */
    public function show($id)
    {
        $product = $this->productRepo->find($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = $this->productRepo->update($id, $request);
        ApiService::_success($product);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $product = $this->productRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}