<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Product;

use Modules\Product\Entities\ProductVariant;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{

    public function all()
    {
        return ProductVariant::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function create($data)
    {
        $feature =  ProductVariant::query()->create($data);
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
            $feature = ProductVariant::query()->where('id', $id)->with(['product'])->firstOrFail();
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
