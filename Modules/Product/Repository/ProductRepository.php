<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Comment\Enums\CommentStatus;
use Modules\Product\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Entities\IncredibleProduct;
use Hilsonxhero\ElasticVision\Domain\Syntax\Term;
use Hilsonxhero\ElasticVision\Domain\Syntax\Range;
use Hilsonxhero\ElasticVision\Domain\Syntax\Terms;
use Hilsonxhero\ElasticVision\Domain\Syntax\Nested;
use Hilsonxhero\ElasticVision\Domain\Syntax\Matching;
use Modules\Product\Enums\ProductQuestionStatusStatus;
use Hilsonxhero\ElasticVision\Domain\Syntax\MatchPhrase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Hilsonxhero\ElasticVision\Domain\Syntax\Compound\BoolQuery;
use Hilsonxhero\ElasticVision\Infrastructure\Scout\ElasticEngine;

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

    public function promotions()
    {
        $query = IncredibleProduct::query()->with('variant')->orderBy('created_at', 'desc')->groupBy('product_id');

        return $query->paginate(20);
    }

    public function getBestSelling()
    {
        $query = Product::query()->withCount('orders')->orderBy('orders_count', 'desc')->whereHas('orders');
        return $query->take(20)->get();
    }

    public function search($query)
    {
        $products = Product::search($query)
            ->field('title_fa')
            ->field('title_en')
            ->filter(new MatchPhrase('status', ProductStatus::ENABLE->value))->take(6)
            ->get();
        return $products;
    }

    public function filters($query, $category)
    {
        $boolQuery = new BoolQuery();

        $search = Product::search($query)
            ->field('title_fa')
            ->field('title_en')
            ->filter(new MatchPhrase('status', ProductStatus::ENABLE->value))
            ->must(new Nested('category', new MatchPhrase('category.id', $category->id)));

        if (request()->filled('available_stock')) {
            $search->filter(new Term('has_stock', true));
        }

        if (request()->filled('feature_id')) {
            foreach (request()->feature_id as $key => $value) {
                $query = new BoolQuery();
                $query->must(new MatchPhrase('features.feature_id', $key));
                $query->must(new Terms('features.feature_value_id', $value));
                $boolQuery->add('must', new Nested('features', $query));
            }
        }

        if (request()->filled('max_price') && request()->filled('min_price')) {
            $boolQuery->add('must', new Nested('variants', new Range(
                'variants.selling_price',
                ['gte' => request()->min_price]
            )));
            $boolQuery->add('must', new Nested('variants', new Range(
                'variants.selling_price',
                ['lte' => request()->max_price]
            )));
            $boolQuery->add('must_not', new Nested('variants', new Range(
                'variants.selling_price',
                ['lt' => request()->min_price]
            )));
            $boolQuery->add('must_not', new Nested('variants', new Range(
                'variants.selling_price',
                ['gt' => request()->max_price]
            )));
        }

        $search->newCompound($boolQuery);

        $products = $search->paginate(15);

        // return ElasticEngine::debug()->json();

        return $products;
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
        return $product->combinations;
    }

    public function allActive()
    {
        return Product::orderBy('created_at', 'desc')
            ->where('status', ProductStatus::ENABLE->value)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $product = Product::query()->with('variants')->create([
            'title_fa' => $data->title_fa,
            'title_en' => $data->title_en,
            'review' => $data->review,
            'category_id' => $data->category_id,
            'brand_id' => $data->brand_id,
            'status' => $data->status,
            'delivery_id' => $data->delivery
        ]);

        base64(json_decode($data->image)) ? $product->addMediaFromBase64(json_decode($data->image))->toMediaCollection('main')
            : $product->addMedia($data->image)->toMediaCollection('main');

        return $product;
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
        $comments = $this->find($id)->comments()->where('status', CommentStatus::Approved)->with('user')->paginate(10);
        return $comments;
    }


    public function questions($id)
    {
        $questions = $this->find($id)->questions()->where('status', ProductQuestionStatusStatus::Approved)->with(['user', 'replies'])->paginate(10);
        return $questions;
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
            $product = Product::query()->where('id', $id)->with($relationships)->withCount('orders')->firstOrFail();
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
