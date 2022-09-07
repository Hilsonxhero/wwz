<?php

namespace Modules\Web\Http\Controllers\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Modules\Page\Entities\Page;
use Illuminate\Routing\Controller;
use Modules\Banner\Entities\Banner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Modules\Article\Repository\ArticleRepositoryInterface;
use Modules\Article\Transformers\ArticleResource;
use Modules\Setting\Transformers\SettingBannerResource;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\IncredibleProductResource;
use Modules\Product\Repository\IncredibleProductRepositoryInterface;

class LandingController extends Controller
{
    private $productRepo;
    private $articleRepo;
    private $IncredibleProductRepo;


    public function __construct(
        ProductRepositoryInterface $productRepo,
        IncredibleProductRepositoryInterface $IncredibleProductRepo,
        ArticleRepositoryInterface $articleRepo
    ) {
        $this->productRepo = $productRepo;
        $this->articleRepo = $articleRepo;
        $this->IncredibleProductRepo = $IncredibleProductRepo;
    }

    public function index()
    {
        $incredible_products = $this->IncredibleProductRepo->take();
        $landing_page = Page::query()->where('title_en', 'landing')->first();
        $header_banners = $landing_page->banners()->where('type', 'hero')->get();
        $top_banners = $landing_page->banners()->where('type', 'top')->get();
        $middle_banners = $landing_page->banners()->where('type', 'middle')->get();
        $articles = $this->articleRepo->take();
        $data = [
            'incredible_products' => IncredibleProductResource::collection($incredible_products),
            'header_banners' =>  SettingBannerResource::collection($header_banners),
            'top_banners' =>  SettingBannerResource::collection($top_banners),
            'middle_banners' =>  SettingBannerResource::collection($middle_banners),
            'articles' =>  ArticleResource::collection($articles),
        ];

        ApiService::_success($data);
    }
}
