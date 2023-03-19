<?php

namespace Modules\Product\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\ProductResource;
use Illuminate\Support\Facades\Cache;

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
     * Display a listing of the resource.
     * @return Response
     */
    public function getBestSelling()
    {
        $products = $this->productRepo->getBestSelling();
        return ProductResource::collection($products);
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
    public function show(Request $request, $id)
    {
        // return  visits('Modules\Product\Entities\Product')->topIds(10);

        $product = $this->productRepo->show($id);
        visits($product)->increment();

        // return   visits($product)->count();

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
