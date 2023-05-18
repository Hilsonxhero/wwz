<?php

namespace Modules\Setting\Repository;

use App\Services\ApiService;
use Modules\Page\Entities\Page;
use Modules\Banner\Entities\Banner;
use Modules\Shipment\Entities\Shipment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Banner\Enum\BannerStatus;

class SettingBannerRepository implements SettingBannerRepositoryInterface
{

    public function all()
    {
        return Banner::query()->where('bannerable_type', Page::class)->orderByDesc('created_at')->paginate();
    }

    public function allAd()
    {
        return Banner::query()->where('bannerable_type', Page::class)->orderByDesc('created_at')->paginate();
    }

    public function allActive()
    {
        return Banner::query()->where('bannerable_type', Page::class)->with('status', BannerStatus::ENABLE)
            ->orderByDesc('created_at')->paginate();
    }

    public function create($data)
    {
        $page = Page::query()->where('id', $data['page'])->first();

        $banner =  $page->banners()->create($data);;
        return $banner;
    }
    public function update($id, $data)
    {
        $banner = $this->find($id);
        $banner->update($data);
        return $banner;
    }
    public function show($id)
    {
        $banner = $this->find($id);
        return $banner;
    }

    public function find($id)
    {
        try {
            $banner = Banner::query()->where('bannerable_type', Page::class)->where('id', $id)->firstOrFail();
            return $banner;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $banner = $this->find($id);
        $banner->delete();
    }
}
