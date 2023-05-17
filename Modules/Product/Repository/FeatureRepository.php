<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;
use Modules\Product\Enums\FeatureStatus;

class FeatureRepository implements FeatureRepositoryInterface
{

    public function all()
    {
        return Feature::orderBy('created_at', 'desc')
            ->with(['parent', 'category'])
            ->paginate();
    }

    public function allActive()
    {
        return Feature::orderBy('created_at', 'desc')
            ->where('status', FeatureStatus::ENABLE)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $feature =  Feature::query()->create($data);
        return $feature;
    }
    public function update($id, $data)
    {
        $feature = $this->find($id);
        $feature->update($data);
        return $feature;
    }
    public function show($id)
    {
        $feature = $this->find($id);
        return $feature;
    }

    public function select($id, $q = '')
    {
        // return Feature::select('id', 'title')->whereNot('id', $id)->orderBy('created_at', 'desc')
        //     ->get();



        $query =  Feature::select('id', 'title', 'parent_id')->orderBy('created_at', 'desc');


        $query->when(request()->has('q'), function ($query) use ($q) {
            $query->where('title', 'LIKE', "%" . $q . "%");
        });

        $query->when(request()->input('doesnt_have_parent'), function ($query) use ($q) {
            $query->whereNotNull('parent_id');
        });

        return $query->paginate();
    }

    public function values($id)
    {
        $feature = $this->find($id);
        return $feature->values()->orderByDesc('created_at')->with('feature')->paginate();
    }


    public function find($id)
    {
        try {
            $feature = Feature::query()->where('id', $id)->firstOrFail();
            return $feature;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $feature = $this->find($id);
        return $feature->delete();
    }
}
