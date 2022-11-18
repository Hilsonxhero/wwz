<?php

namespace Modules\Web\Http\Controllers\App;

use Cart;
use App\Services\ApiService;
use Modules\Page\Entities\Page;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;
use Modules\Article\Transformers\ArticleResource;
use Modules\Setting\Transformers\SettingBannerResource;
use Modules\Article\Repository\ArticleRepositoryInterface;
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
        $articles = $this->articleRepo->take();
        $data = [
            'incredible_products' => IncredibleProductResource::collection($incredible_products),
            'header_banners' =>  array(),
            'top_banners' =>  array(),
            'middle_banners' =>  array(),
            'articles' =>  ArticleResource::collection($articles),
        ];

        if ($landing_page) {

            $data['banners']['header_banners'] =
                SettingBannerResource::collection($landing_page->banners()->where('type', 'hero')->get());
            $data['banners']['top_banners'] =
                SettingBannerResource::collection($landing_page->banners()->where('type', 'top')->get());
            $data['banners']['middle_banners'] =
                SettingBannerResource::collection($landing_page->banners()->where('type', 'middle')->get());
        }

        ApiService::_success($data);
    }
}
