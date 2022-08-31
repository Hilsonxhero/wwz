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

    public function create($data)
    {
        $page =  Page::query()->create([
            'title' => $data->title,
            'title_en' => $data->title_en,
            'content' => $data->content,
        ]);
        return $page;
    }
    public function update($id, $data)
    {
        $page = $this->find($id);
        $page->update([
            'title' => $data->title,
            'title_en' => $data->title_en,
            'content' => $data->content,
        ]);
        return $page;
    }
    public function show($id)
    {
        $page = $this->find($id);
        return $page;
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
