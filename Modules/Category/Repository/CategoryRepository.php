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

    public function allActive()
    {
        return Category::orderBy('created_at', 'desc')
            ->where('status', Category::ENABLE_STATUS)
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
        $category = $this->find($id)->delete();
    }
}
