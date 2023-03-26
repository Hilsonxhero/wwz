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
        $variant =  ProductVariant::query()->create($data);

        $data = [];

        $combinations = request()->input('combinations');

        foreach ($combinations as $key => $combination) {
            $data[$key] = [
                'product_variant_id' => $variant->id,
                'variant_id' => $combination
            ];
        }

        $variant->combinations()->insert($data);

        return $variant;
    }
    public function update($id, $data)
    {
        $variant = $this->find($id);
        $variant->update($data);
        return $variant;
    }
    public function show($id)
    {
        $variant = $this->find($id);
        return $variant;
    }

    public function values($id)
    {
        $variant = $this->find($id);
        return $variant->values;
    }


    public function find($id, $relationships = [])
    {
        try {
            $variant = ProductVariant::query()->where('id', $id)->with([...$relationships, 'warranty', 'shipment', 'combinations'])->firstOrFail();
            return $variant;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $variant = $this->find($id);
        return $variant->delete();
    }
}
