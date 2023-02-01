<?php

namespace Modules\Category\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Category\Entities\Category;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function all()
    {
        return Category::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function group()
    {
        return Category::query()

            ->whereNull('parent_id')
            ->with(['children'])
            ->withCount(['products'])
            ->get();
    }


    public function mainCategories()
    {
        return Category::query()->whereNull('parent_id')->orderBy('created_at', 'desc')
            ->get();
    }

    public function select($q)
    {
        $query =  Category::select('id', 'title')->orderBy('created_at', 'desc');


        $query->when(request()->has('q'), function ($query) use ($q) {
            $query->where('title', 'LIKE', "%" . $q . "%");
        });

        return $query->take(25)->get();
    }

    public function allActive()
    {
        return Category::orderBy('created_at', 'desc')
            ->where('status', Category::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $category =  Category::query()->create($data);
        return $category;
    }
    public function update($id, $data)
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }
    public function show($id)
    {
        $category = $this->find($id);
        return $category;
    }

    public function find($id)
    {
        try {
            $category = Category::query()->where('id', $id)->firstOrFail();
            return $category;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $category = $this->find($id);
        $category->clearMediaCollectionExcept();
        $category->delete();
    }
}
