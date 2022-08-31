<?php

namespace Modules\Web\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Setting\Transformers\SettingResource;
use Modules\Setting\Repository\SettingRepositoryInterface;

class InitController extends Controller
{
    private $settingrRepo;
    public function __construct(SettingRepositoryInterface $settingrRepo)
    {
        $this->settingrRepo = $settingrRepo;
    }

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $settings = $this->settingrRepo->all();
        return SettingResource::collection($settings);
    }
}
