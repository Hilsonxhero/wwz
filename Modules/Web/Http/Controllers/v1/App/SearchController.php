<?php

namespace Modules\Web\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;
use Modules\Brand\Transformers\BrandResource;
use Modules\Product\Transformers\App\FeatureResource;
use Modules\Category\Transformers\App\CategoryResource;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\App\ProductSearchResource;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Hilsonxhero\ElasticVision\Infrastructure\Scout\ElasticEngine;
use Hilsonxhero\ElasticVision\Infrastructure\Elastic\ElasticIndexAdapter;
use Hilsonxhero\ElasticVision\Infrastructure\Elastic\ElasticDocumentAdapter;

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

    /**
     * Display search results .
     *
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        $category = $this->categoryRepo->findBySlug($request->category_slug);
        // return $this->productRepo->filters($request->q, $category);
        $products = $this->productRepo->filters($request->q, $category);
        // return $products;
        $products_collection =  ProductSearchResource::collection($products);

        ApiService::_success(
            array(
                'products' => $products_collection->items(),
                'features' => FeatureResource::collection($category->features),
                'brands' =>  BrandResource::collection($category->brands),
                'category' => new CategoryResource($category),
                'pager' => array(
                    'pages' => $products_collection->lastPage(),
                    'total' => $products_collection->total(),
                    'current_Page' => $products_collection->currentPage(),
                    'per_page' => $products_collection->perPage(),

                ),
            )
        );
    }
}
