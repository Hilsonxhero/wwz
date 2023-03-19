<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\IncredibleProduct;
use Hilsonxhero\ElasticVision\Domain\Syntax\Nested;
use Hilsonxhero\ElasticVision\Domain\Syntax\MatchPhrase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IncredibleProductRepository implements IncredibleProductRepositoryInterface
{

    public function all()
    {
        return IncredibleProduct::orderBy('created_at', 'desc')
            ->paginate();
    }


    public function take()
    {
        return IncredibleProduct::with('variant')
            ->orderBy('created_at', 'desc')
            ->groupBy('product_id')
            ->take(15)
            ->get();
    }
    public function promotions()
    {
        // $query = IncredibleProduct::query()->with('variant')->orderBy('created_at', 'desc')->groupBy('product_id');
        $search = IncredibleProduct::search();

        if (request()->filled('category_id')) {
            $search->must(new Nested('category', new MatchPhrase('category.main_parent.id', request()->category_id)));
        }

        return $search->paginate(20);
    }


    public function create($data)
    {
        $product =  IncredibleProduct::query()->create([
            'product_id' => $data->product,
            'variant_id' => $data->variant,
            'discount' => $data->discount,
            'discount_expire_at' => createDatetimeFromFormat($data->expire_at),
        ]);
        return $product;
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

    public function select($id)
    {
        return IncredibleProduct::select('id', 'title')->whereNot('id', $id)->orderBy('created_at', 'desc')
            ->get();
    }

    public function values($id)
    {
        $product = $this->find($id);
        return $product->values()->orderByDesc('created_at')->with('feature')->paginate();
    }


    public function find($id)
    {
        try {
            $product = IncredibleProduct::query()->where('id', $id)->firstOrFail();
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
