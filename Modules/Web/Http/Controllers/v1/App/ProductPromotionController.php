<?php

namespace Modules\Web\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Transformers\CategoryResource;
use Modules\Product\Repository\IncredibleProductRepositoryInterface;
use Modules\Product\Transformers\IncredibleProductResource;

class ProductPromotionController extends Controller
{
    private $IncredibleProductRepo;
    private $categoryRepo;
    public function __construct(
        IncredibleProductRepositoryInterface $IncredibleProductRepo,
        CategoryRepositoryInterface $categoryRepo
    ) {
        $this->IncredibleProductRepo = $IncredibleProductRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display  promotions .
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->IncredibleProductRepo->promotions();

        $incredible_products = IncredibleProductResource::collection($products);

        // return $products;

        ApiService::_success(
            array(
                'products' => $incredible_products,
                'pager' => array(
                    'pages' => $incredible_products->lastPage(),
                    'total' => $incredible_products->total(),
                    'current_page' => $incredible_products->currentPage(),
                    'per_page' => $incredible_products->perPage(),
                ),
                'categories' => CategoryResource::collection($this->categoryRepo->mainCategories())
            )
        );
    }
}
