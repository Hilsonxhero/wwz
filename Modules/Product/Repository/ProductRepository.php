<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Product;

class ProductRepository implements ProductRepositoryInterface
{

    public function all()
    {
        return Product::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function variants($id)
    {
        $product = $this->find($id);
        return $product->variants()->paginate();
    }

    public function allActive()
    {
        return Product::orderBy('created_at', 'desc')
            ->where('status', Product::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $product =  Product::query()->create($data);
        return $product;
    }

    public function createVariants($id, $variants)
    {
        $product = $this->find($id);
        $variants = collect(json_decode($variants));

        foreach ($variants as $key => $variant) {
            $producy_variant = $product->variants()->create([
                'warranty_id' => $variant->warranty,
                'shipment_id' => $variant->shipment,
                'price' => $variant->price,
                'discount' => $variant->discount,
                'discount_price' => $variant->discount_price,
                'stock' => $variant->stock,
                'weight' => $variant->weight,
                'order_limit' => $variant->order_limit,
                'default_on' => 1,
            ]);

            foreach ($variant->combinations as $combination) {
                $producy_variant->combinations()->firstOrCreate([
                    'variant_id' => $combination->id,
                ]);
            }
        }
    }

    public function updateVariants($id, $variants)
    {
        $product = $this->find($id);
        $product->variants()->delete();
        $product->combinations()->delete();
        $variants = collect(json_decode($variants));

        foreach ($variants as $key => $variant) {
            $producy_variant = $product->variants()->create([
                'warranty_id' => $variant->warranty,
                'shipment_id' => $variant->shipment,
                'price' => $variant->price,
                'discount' => $variant->discount,
                'discount_price' => $variant->discount_price,
                'stock' => $variant->stock,
                'weight' => $variant->weight,
                'order_limit' => $variant->order_limit,
                'default_on' => 1,
            ]);

            foreach ($variant->combinations as $combination) {
                $producy_variant->combinations()->firstOrCreate([
                    'variant_id' => $combination->id,
                ]);
            }
        }
    }


    public function update($id, $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }
    public function show($id)
    {
        $product = $this->find($id);
        return $product;
    }

    public function values($id)
    {
        $product = $this->find($id);
        return $product->values;
    }


    public function find($id)
    {
        try {
            $product = Product::query()->where('id', $id)->firstOrFail();
            return $product;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $product = $this->find($id);
        return $product->delete();
    }
}
