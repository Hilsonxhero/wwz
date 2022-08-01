<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\FeatureValue;

class FeatureValueRepository implements FeatureValueRepositoryInterface
{

    public function all()
    {
        return FeatureValue::orderBy('created_at', 'desc')
            ->with('feature')
            ->paginate();
    }

    public function allActive()
    {
        return FeatureValue::orderBy('created_at', 'desc')
            ->where('status', FeatureValue::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $category =  FeatureValue::query()->create($data);
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
            $category = FeatureValue::query()->where('id', $id)->with(['feature'])->firstOrFail();
            return $category;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $category = $this->find($id);
        $category->delete();
    }
}
