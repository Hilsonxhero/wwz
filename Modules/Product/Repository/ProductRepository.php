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
                $query->where('discount', 0)->whereNull('discount_expire_at')->orWhereDate('discount_expire_at', '<', now());
            });
        });
        return $query->take(25)->get();
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
            'delivery_id' => $data->delivery
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
                'price' => $variant->rrp_price,
                'discount' => $variant->discount,
                'discount_price' => $variant->rrp_price * $variant->discount / 100,
                'stock' => $variant->stock,
                'weight' => $variant->weight,
                'order_limit' => $variant->order_limit,
                'default_on' => $variant->default_on,
                'discount_expire_at' => $variant->discount_expire_at ? createDatetimeFromFormat($variant->discount_expire_at) : null
            ]);

            foreach ($variant->combinations as $combination) {
                $producy_variant->combinations()->firstOrCreate([
                    'variant_id' => $combination->variant_id,
                ]);
            }
        }
    }

    public function updateVariants($product, $variants)
    {
        $variants = collect(json_decode($variants), true);

        DB::transaction(function () use ($variants, $product) {
            foreach ($variants as $key => $variant) {
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
                        'default_on' => $variant->default_on,
                        'discount_expire_at' => $variant->discount_expire_at ? createDatetimeFromFormat($variant->discount_expire_at) : null
                    ],
                );
                foreach ($variant->combinations as $combination) {
                    $producy_variant->combinations()->updateOrCreate(
                        ['product_variant_id' => $producy_variant->id, 'variant_id' => $combination->variant_id],
                        [
                            'variant_id' => $combination->variant_id,
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
            'delivery_id' => $data->delivery
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
        // $product = $this->find($id)->with(['features.childs']);
        $product = $this->find($id)->load(['variants', 'productFeatures', 'combinations', 'category', 'delivery'])->loadAvg('scores', 'value')->loadCount('comments');
        return $product;
    }

    public function values($id)
    {
        $product = $this->find($id);
        return $product->values;
    }

    public function comments($id)
    {
        $comments = $this->find($id)->comments()->with('user')->paginate(10);
        return $comments;
    }


    public function find($id, $relationships = [])
    {
        // [
        //     'productFeatures' => [
        //         'feature:id,title,parent_id' => [
        //             'parent:id,title'
        //         ]
        //     ],
        // 'combinations' => [
        //     'variant:id,name,value,variant_group_id' => [
        //         'group:id,name,type'
        //     ]
        // ],
        // ]
        try {
            $product = Product::query()->where('id', $id)->with($relationships)->firstOrFail();
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
