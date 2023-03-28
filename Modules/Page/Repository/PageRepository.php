<?php

namespace Modules\Page\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Page\Entities\Page;


class PageRepository implements PageRepositoryInterface
{

    public function all()
    {
        return Page::orderBy('created_at', 'desc')
            ->paginate();
    }
    public function banners($banners, $type)
    {
        return $banners->where('type', $type)->get();
    }


    public function create($data)
    {
        $page =  Page::query()->create($data);
        return $page;
    }
    public function update($id, $data)
    {
        $page = $this->find($id);
        $page->update($data);
        return $page;
    }
    public function show($id)
    {
        $page = $this->find($id);
        return $page;
    }
    public function findByTitle($title)
    {
        try {
            $page = Page::query()->where('title_en', $title)->firstOrFail();
            return $page;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function find($id)
    {
        try {
            $page = Page::query()->where('id', $id)->firstOrFail();
            return $page;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $page = $this->find($id);
        $page->delete();
    }
}
