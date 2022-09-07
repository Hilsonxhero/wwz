<?php

namespace Modules\Product\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Feature;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\App\ProductFeatureResource;
use Modules\Product\Transformers\ProductResource;

class ProductController extends Controller
{
    private $productRepo;
    public function __construct(ProductRepositoryInterface $productRepo)
    {

        $this->productRepo = $productRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
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
        $product = $this->productRepo->find($id);
        // $combinations = collect($product->combinations)->unique('variant_id');
        // $grouped = $combinations->groupBy('variant.group')->transform(function ($item, $key) {
        //     return ['group' => json_decode($key), 'values' => $item];
        // })->values();
        // return $grouped;

        return new ProductResource($product);
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
