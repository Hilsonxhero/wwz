<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;

class FeatureRepository implements FeatureRepositoryInterface
{

    public function all()
    {
        return Feature::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Feature::orderBy('created_at', 'desc')
            ->where('status', Feature::ENABLE_STATUS)
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

    public function values($id)
    {
        $feature = $this->find($id);
        return $feature->values;
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
