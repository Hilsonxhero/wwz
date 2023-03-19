<?php

namespace Modules\Category\Repository;

use App\Services\ApiService;
use Modules\Category\Entities\Category;
use Modules\Category\Enum\CategoryStatus;
use Hilsonxhero\ElasticVision\Domain\Syntax\MatchPhrase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function search($query)
    {
        $categories = Category::search($query)
            ->field('title')
            ->field('title_en')
            ->filter(new MatchPhrase('status', CategoryStatus::ENABLE->value))->take(15)->get();
        return $categories;
    }


    public function mainCategories()
    {
        return Category::query()->whereNull('parent_id')->where('status', CategoryStatus::ENABLE->value)->orderBy('created_at', 'desc')
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
            ->where('status', CategoryStatus::ENABLE->value)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $category =  Category::query()->create($data);
        // $category->save();
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
    public function findBySlug($slug)
    {
        try {
            $category = Category::query()->where('slug', $slug)->firstOrFail();
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
