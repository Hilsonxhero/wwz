<?php

namespace Modules\Setting\Repository;

use App\Services\ApiService;
use Modules\Page\Entities\Page;
use Modules\Banner\Entities\Banner;
use Modules\Shipment\Entities\Shipment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        return Banner::query()->where('bannerable_type', Page::class)->with('status', Banner::ENABLE_STATUS)
            ->orderByDesc('created_at')->paginate();
    }

    public function create($data)
    {
        $page = Page::query()->where('id', $data->page)->first();

        $banner =  $page->banners()->create([
            'title' => $data->title,
            'url' => $data->url,
            'type' => $data->type,
            'status' => $data->status,
        ]);
        base64($data->image) ? $banner->addMediaFromBase64($data->image)->toMediaCollection('main')
            : $banner->addMedia($data->image)->toMediaCollection('main');
        return $banner;
    }
    public function update($id, $data)
    {
        $banner = $this->find($id);
        $banner->update([
            'title' => $data->title,
            'url' => $data->url,
            'type' => $data->type,
            'status' => $data->status,
            'bannerable_id' => $data->page,
            'bannerable_type' => Page::class,
        ]);

        if ($data->banner) {
            $banner->clearMediaCollectionExcept('main');
            base64($data->banner) ? $banner->addMediaFromBase64($data->banner)->toMediaCollection('main')
                : $banner->addMedia($data->banner)->toMediaCollection('main');
        }
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
