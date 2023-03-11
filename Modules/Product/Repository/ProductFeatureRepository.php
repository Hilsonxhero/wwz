<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductFeature;

class ProductFeatureRepository implements ProductFeatureRepositoryInterface
{

    public function all()
    {
        return ProductFeature::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function create($data)
    {
        $feature =  ProductFeature::query()->create($data);
        // Product::query()->where('id', 2)->first()->searchable();
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
            $feature = ProductFeature::query()->where('id', $id)->with(['feature', 'quantity'])->firstOrFail();
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
