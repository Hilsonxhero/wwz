<?php

namespace Modules\Web\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Category\Transformers\App\CategoryResource;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\App\ProductSearchResource;

class SearchController extends Controller
{
    private $productRepo;
    private $categoryRepo;
    public function __construct(
        ProductRepositoryInterface $productRepo,
        CategoryRepositoryInterface $categoryRepo
    ) {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display search results .
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $categories = $this->categoryRepo->search($request->q);

        $products = $this->productRepo->search($request->q);

        ApiService::_success(
            array(
                'categories' => CategoryResource::collection($categories),
                'products' => ProductSearchResource::collection($products)
            )
        );
    }
}
