<?php

namespace Modules\Setting\Repository;

use App\Services\ApiService;
use Modules\Page\Entities\Page;
use Modules\Banner\Entities\Banner;
use Modules\Setting\Entities\Setting;
use Modules\Shipment\Entities\Shipment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingRepository implements SettingRepositoryInterface
{

    public function all()
    {
        return Setting::all()->keyBy->name;
    }


    public function create($data)
    {
        $page = Page::query()->where('id', $data->page)->first();

        $setting =  $page->banners()->create([
            'title' => $data->title,
            'url' => $data->url,
            'type' => $data->type,
            'status' => $data->status,
        ]);
        base64($data->banner) ? $setting->addMediaFromBase64($data->banner)->toMediaCollection('main')
            : $setting->addMedia($data->banner)->toMediaCollection('main');
        return $setting;
    }
    public function update($id, $data)
    {
        $setting = $this->find($id);
        $setting->update([
            'title' => $data->title,
            'url' => $data->url,
            'type' => $data->type,
            'status' => $data->status,
            'bannerable_id' => $data->page,
            'bannerable_type' => Page::class,
        ]);

        if ($data->filled('banner')) {
            $setting->clearMediaCollectionExcept();
            base64($data->banner) ? $setting->addMediaFromBase64($data->banner)->toMediaCollection('main')
                : $setting->addMedia($data->banner)->toMediaCollection('main');
        }
        return $setting;
    }
    public function show($id)
    {
        $setting = $this->find($id);
        return $setting;
    }

    public function find($name)
    {
        $setting = Setting::query()->where('name', $name)->first();
        return $setting;
        // try {
        //     $setting = Setting::query()->where('name', $name)->firstOrFail();
        // } catch (ModelNotFoundException $e) {
        //     return  ApiService::_response(trans('response.responses.404'), 404);
        // }
    }
    public function delete($id)
    {
        $setting = $this->find($id);
        $setting->delete();
    }
}
