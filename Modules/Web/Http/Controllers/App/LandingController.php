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

        // $redis = Redis::connection();
        // try {
        //     return   $redis->ping();
        // } catch (Exception $e) {
        //     return  $e->getMessage();
        // }

        // $storage = Redis::connection();
        // $views = $storage->incr('article:1111:views');
        // return $views;

        Cookie::queue(
            'private_key',
            'www3434343434',
            45000,
            null,
            null,
            false,
            false,
            false,
            'Strict'
        );


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
