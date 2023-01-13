<?php

namespace Modules\Setting\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Transformers\SettingBannerResource;
use Modules\Setting\Http\Requests\SettingBannerRequest;
use Modules\Setting\Repository\SettingBannerRepositoryInterface;

class BannerController extends Controller
{

    private $bannerRepo;

    public function __construct(SettingBannerRepositoryInterface $bannerRepo)
    {
        $this->bannerRepo = $bannerRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $banners = $this->bannerRepo->all();
        return SettingBannerResource::collection($banners);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SettingBannerRequest $request)
    {
        $this->bannerRepo->create($request);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $banner = $this->bannerRepo->show($id);
        return new SettingBannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SettingBannerRequest $request, $id)
    {
        $this->bannerRepo->update($id, $request);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $banner = $this->bannerRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}