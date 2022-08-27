<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use League\Glide\Api\Api;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

class ProductRepository implements ProductRepositoryInterface
{

    public function all($q)
    {
        $query = Product::query()->orderBy('created_at', 'desc');
        if (request()->has('q')) {
            $query->where('title_fa', 'LIKE', "%" . $q . "%");
        }

        return $query->paginate();
    }
    public function incredibles()
    {
        $query = Product::query()->orderBy('created_at', 'desc');

        return $query->paginate();
    }


    public function select($q)
    {
        $query =  Product::select('id', 'title_fa')->orderBy('created_at', 'desc');

        $query->when(request()->has('q'), function ($query) use ($q) {
            $query->where('title_fa', 'LIKE', "%" . $q . "%");
        });

        $query->when(request()->input('doesnt_have_incredble'), function ($query) use ($q) {
            $query->whereDoesntHave('incredibles');
        });

        $query->when(request()->input('doesnt_have_discount'), function ($query) use ($q) {
            $query->whereHas('variants', function ($query) {
                $query->where('discount', 0)->whereDate('discount_expire_at', '<', now());
            });
        });
        return $query->paginate();
    }

    public function variants($id)
    {
        $product = $this->find($id);
        return $product->variants()->paginate();
    }

    public function combinations($id)
    {
        $product = $this->find($id);
        return $product->combinations();
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
        $product = Product::query()->create([
            'title_fa' => $data->title_fa,
            'title_en' => $data->title_en,
            'review' => $data->review,
            'category_id' => $data->category_id,
            'brand_id' => $data->brand_id,
            'status' => $data->status,
        ]);

        $this->createVariants($product, $data->input('variants'));

        base64(json_decode($data->image)) ? $product->addMediaFromBase64(json_decode($data->image))->toMediaCollection('main')
            : $product->addMedia($data->image)->toMediaCollection('main');

        return $product;
    }

    public function createVariants($product, $variants)
    {
        $variants = collect(json_decode($variants));

        foreach ($variants as $key => $variant) {
            $producy_variant = $product->variants()->create([
                'warranty_id' => $variant->warranty,
                'shipment_id' => $variant->shipment,
                'price' => $variant->price,
                'discount' => $variant->discount,
                'discount_price' => $variant->price * $variant->discount / 100,
                'stock' => $variant->stock,
                'weight' => $variant->weight,
                'order_limit' => $variant->order_limit,
                'default_on' => 1,
                'discount_expire_at' => createDatetimeFromFormat($variant->discount_expire_at)
            ]);

            foreach ($variant->combinations as $combination) {
                $producy_variant->combinations()->firstOrCreate([
                    'variant_id' => $combination->id,
                ]);
            }
        }
    }

    public function updateVariants($product, $variants)
    {
        $variants = collect(json_decode($variants), true);




        DB::transaction(function () use ($variants, $product) {
            foreach ($variants as $key => $variant) {
                // ApiService::_response($variants, 403);
                $producy_variant = $product->variants()->updateOrCreate(
                    ['product_id' => $variant->product, 'id' => $variant->id],
                    [
                        'warranty_id' => $variant->warranty,
                        'shipment_id' => $variant->shipment,
                        'price' => $variant->rrp_price,
                        'discount' => $variant->discount,
                        'discount_price' => $variant->rrp_price * $variant->discount / 100,
                        'stock' => $variant->stock,
                        'weight' => $variant->weight,
                        'order_limit' => $variant->order_limit,
                        'default_on' => 1,
                        'discount_expire_at' => createDatetimeFromFormat($variant->discount_expire_at)
                    ],
                );





                foreach ($variant->combinations as $combination) {
                    $producy_variant->combinations()->updateOrCreate(
                        ['product_variant_id' => $producy_variant->id, 'variant_id' => $combination->id],
                        [
                            'variant_id' => $combination->id,
                        ]
                    );
                }
            }
        });
    }


    public function update($id, $data)
    {
        $product = $this->find($id);

        $product->update([
            'title_fa' => $data->title_fa,
            'title_en' => $data->title_en,
            'review' => $data->review,
            'category_id' => $data->category_id,
            'brand_id' => $data->brand_id,
            'status' => $data->status,
        ]);

        $this->updateVariants($product, $data->input('variants'));

        if ($data->image) {
            $product->clearMediaCollectionExcept('main');
            base64(json_decode($data->image)) ? $product->addMediaFromBase64(json_decode($data->image))->toMediaCollection('main')
                : $product->addMedia($data->image)->toMediaCollection('main');
        }

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
            return ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function delete($id)
    {
        $product = $this->find($id);
        return $product->delete();
    }
}
