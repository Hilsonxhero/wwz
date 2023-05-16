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
    public function getBestSelling()
    {
        $products = $this->productRepo->getBestSelling();
        return ProductResource::collection($products);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $product = $this->productRepo->find($id);
        $product = new ProductResource($product);
        // visits('Modules\Product\Entities\Product')->topIds(10);
        // visits($product)->increment(1);
        return ApiService::_success(array(
            'product' => $product,
            'related_products' => $this->productRepo->relatedProducts($product->category->id)
        ));
    }
}
