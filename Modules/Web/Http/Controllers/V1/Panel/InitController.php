<?php

namespace Modules\Web\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Modules\Page\Entities\Page;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Web\Transformers\InitConfigResource;
use Modules\Setting\Transformers\SettingResource;
use Modules\Setting\Transformers\SettingBannerResource;
use Modules\Setting\Repository\SettingRepositoryInterface;
use Modules\Setting\Repository\SettingBannerRepositoryInterface;

class InitController extends Controller
{
    private $settingrRepo;
    private $bannerRepo;
    public function __construct(SettingRepositoryInterface $settingrRepo, SettingBannerRepositoryInterface $bannerRepo)
    {
        $this->settingrRepo = $settingrRepo;
        $this->bannerRepo = $bannerRepo;
    }

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {

        $settings = $this->settingrRepo->all();
        $pages = Page::query()->where('title_en', 'all')->first();

        $data = [
            'config' => SettingResource::collection($settings),
            'banners' => [],
        ];

        if ($pages) {
            $top_header_banner = $pages->banners()->where('type', 'header')->where('status', 'enable')->first();
            // if ($top_header_banner) {
            //     $data['banners']['top_header_banner'] = new  SettingBannerResource($top_header_banner);
            // }
        }


        ApiService::_success($data);
        // return SettingResource::collection($settings);
    }
}
