<?php

namespace Modules\Web\Http\Controllers\App;

use Cart;
use App\Services\ApiService;
use Illuminate\Http\Request;

use Modules\Page\Entities\Page;
use Elasticsearch\ClientBuilder;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Cookie;
use JeroenG\Explorer\Domain\Syntax\Term;
use JeroenG\Explorer\Domain\Syntax\Terms;
use JeroenG\Explorer\Domain\Syntax\Nested;
use JeroenG\Explorer\Domain\Syntax\Matching;
use Modules\Article\Transformers\ArticleResource;
use JeroenG\Explorer\Domain\Syntax\Compound\BoolQuery;
use Modules\Setting\Transformers\SettingBannerResource;
use JeroenG\Explorer\Infrastructure\Scout\ElasticEngine;
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

    public function index(Request $request)
    {
        // $hosts = [
        //     [
        //         'host' => 'localhost',
        //         'port' => '9200',
        //         'scheme' => 'http',
        //         'user' => 'elastic',
        //         'pass' => '1Vp*I6enXMZqc8xq1UbY',
        //     ]
        // ];

        // $elasticsearch = ClientBuilder::create()
        //     ->setHosts($hosts)
        //     ->build();

        // Product::query()->where('id', 5)->update([
        //     'status' => 'enable'
        // ]);

        // $search = Product::search('خانه')->filter(new Term('status', 'enable'))->get();

        $search = Product::search()->must(new Matching('title_fa', 'یخچال'))->get();

        // $search = Product::search('')->must(new Nested('category', new Matching('category.title', 'دیجیتال')))->get();

        return ApiService::_success($search);

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
