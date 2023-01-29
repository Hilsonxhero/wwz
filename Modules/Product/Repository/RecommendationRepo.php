<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\Recommendation;
use Modules\Product\Entities\RecommendationProduct;

class RecommendationRepo implements RecommendationRepoInterface
{
    public function get()
    {
        return Recommendation::orderBy('created_at', 'desc')
            ->with(['category'])
            ->get();
    }

    public function all()
    {
        return Recommendation::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function create($data)
    {
        $recommendation =  Recommendation::query()->create($data);
        return $recommendation;
    }
    public function update($id, $data)
    {
        $recommendation = $this->find($id);
        $recommendation->update($data);
        return $recommendation;
    }
    public function show($id)
    {
        $recommendation = $this->find($id);
        return $recommendation;
    }

    public function select($id, $q = '')
    {
        // return Recommendation::select('id', 'title')->whereNot('id', $id)->orderBy('created_at', 'desc')
        //     ->get();



        $query =  Recommendation::select('id', 'title', 'parent_id')->orderBy('created_at', 'desc');


        $query->when(request()->has('q'), function ($query) use ($q) {
            $query->where('title', 'LIKE', "%" . $q . "%");
        });

        $query->when(request()->input('doesnt_have_parent'), function ($query) use ($q) {
            $query->whereNotNull('parent_id');
        });

        return $query->paginate();
    }

    public function find($id)
    {
        try {
            $recommendation = Recommendation::query()->where('id', $id)->firstOrFail();
            return $recommendation;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $recommendation = $this->find($id);
        return $recommendation->delete();
    }
}
