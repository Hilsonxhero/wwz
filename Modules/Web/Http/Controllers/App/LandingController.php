<?php

namespace Modules\Web\Http\Controllers\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Modules\Page\Entities\Page;
use Illuminate\Routing\Controller;
use Modules\Article\Transformers\ArticleResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Page\Repository\PageRepositoryInterface;
use Modules\Article\Repository\ArticleRepositoryInterface;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\IncredibleProductResource;
use Modules\Setting\Transformers\App\SettingBannerResource;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Repository\RecommendationCategoryRepoInterface;
use Modules\Product\Repository\IncredibleProductRepositoryInterface;

class LandingController extends Controller
{
    private $productRepo;
    private $articleRepo;
    private $IncredibleProductRepo;
    private $categoryRepo;
    private $pageRepo;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        ProductRepositoryInterface $productRepo,
        IncredibleProductRepositoryInterface $IncredibleProductRepo,
        ArticleRepositoryInterface $articleRepo,
        PageRepositoryInterface $pageRepo
    ) {
        $this->pageRepo = $pageRepo;
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
        $this->articleRepo = $articleRepo;
        $this->IncredibleProductRepo = $IncredibleProductRepo;
    }

    public function index(Request $request)
    {
        $incredible_products = $this->IncredibleProductRepo->take();
        $main_categories = $this->categoryRepo->mainCategories();
        $landing_page = $this->pageRepo->findByTitle("landing");
        $articles = $this->articleRepo->take();
        $best_selling_products = ProductResource::collection($this->productRepo->getBestSelling());
        $banners = $landing_page->banners();
        $data = [
            'incredible_products' => IncredibleProductResource::collection($incredible_products),
            'best_selling_products' => $best_selling_products,
            'main_categories' => CategoryResource::collection($main_categories),
            'articles' => ArticleResource::collection($articles),
            'banners' =>  array(
                'header_banners' => $landing_page ?
                    SettingBannerResource::collection($banners->where('type', 'hero')->get()) : array(),
                'top_banners' => $landing_page ?
                    SettingBannerResource::collection($banners->where('type', 'top')->get()) : array(),
                'middle_banners' => $landing_page ?
                    SettingBannerResource::collection($banners->where('type', 'middle')->get()) : array()
            )
        ];

        ApiService::_success($data);
    }
}
