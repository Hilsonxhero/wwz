<?php

namespace Modules\Category\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Banner\Entities\Banner;
use Modules\Category\Entities\Category;

class CategoryBannerRepository implements CategoryBannerRepositoryInterface
{

    public function all()
    {
        return Banner::query()->where('bannerable_type', Category::class)->orderByDesc('created_at')->paginate();
    }

    public function create($category, $data)
    {
        $banner = $category->banners()->create([
            'title' => $data->title,
            'url' => $data->url,
            'position' => 0,
            'type' => $data->type,
            'status' => $data->status,
        ]);

        return $banner;
    }

    public function update($id, $data)
    {
        $banner = $this->find($id);
        $banner->update([
            'title' => $data->title,
            'url' => $data->url,
            'position' => 0,
            'type' => $data->type,
            'status' => $data->status,
        ]);
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
            $banner = Banner::query()->where('id', $id)->firstOrFail();
            return $banner;
        } catch (ModelNotFoundException $e) {
            return ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function delete($id)
    {
        $banner = $this->find($id);
        $banner->clearMediaCollectionExcept();
        $banner->delete();
    }
}
