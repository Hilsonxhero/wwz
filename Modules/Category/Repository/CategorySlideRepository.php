<?php

namespace Modules\Category\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Category\Entities\Category;
use Modules\Slide\Entities\Slide;

class CategorySlideRepository implements CategorySlideRepositoryInterface
{

    public function all()
    {
        return Slide::query()->where('slideable_type', Category::class)->orderByDesc('created_at')->paginate();
    }

    public function allActive()
    {
        return Category::orderBy('created_at', 'desc')
            ->where('status', Category::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($category, $data)
    {
        $slide = $category->slides()->create([
            'title' => $data->title,
            'url' => $data->url,
            'position' => 0,
            'type' => $data->type,
            'status' => $data->status,
        ]);

        return $slide;
    }

    public function update($id, $data)
    {
        $slide = $this->find($id);
        $slide->update([
            'title' => $data->title,
            'url' => $data->url,
            'position' => 0,
            'type' => $data->type,
            'status' => $data->status,
        ]);
        return $slide;
    }

    public function show($id)
    {
        $slide = $this->find($id);
        return $slide;
    }

    public function find($id)
    {
        try {
            $slide = Slide::query()->where('id', $id)->firstOrFail();
            return $slide;
        } catch (ModelNotFoundException $e) {
            return ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function delete($id)
    {
        $slide = $this->find($id);
        $slide->clearMediaCollectionExcept();
        $slide->delete();
    }
}
