<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductVariantRepositoryInterface;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Transformers\ProductVariantResourceCollection;

class ProductVariantController extends Controller
{

    private $productRepo;
    private $variantRepo;

    public function __construct(ProductRepositoryInterface $productRepo, ProductVariantRepositoryInterface $variantRepo)
    {
        $this->productRepo = $productRepo;
        $this->variantRepo = $variantRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($id)
    {

        $variants = $this->productRepo->variants($id);
        // ApiService::_response($variants, 403);
        // return  ProductVariantResource::collection($variants);
        return   ProductVariantResource::collection($variants);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
