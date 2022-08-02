<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Product;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\ProductResourceCollection;

class ProductController extends Controller
{

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $products = $this->productRepo->all();
        return new ProductResourceCollection($products);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'title_fa' => ['required', 'min:4'],
            'title_en' => ['required', 'min:4'],
            'review' => ['required', 'min:4'],
            'image' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'status' => ['required', Rule::in(Product::$statuses)],
            "features" => ['array', 'required'],
            "features.*.name" => ['required', 'exists:features,id'],
            "features.*.value" => ['required', 'exists:feature_values,id']
        ]);

        $data = [
            'title_fa' => $request->title_fa,
            'title_en' => $request->title_en,
            'review' => $request->review,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'status' => $request->status,
        ];

        $product = $this->productRepo->create($data);

        $features = collect($request->input('features'));

        $features->each(function ($attr) use ($product) {
            $product->features()->attach($attr['name'], ['feature_value_id' => $attr['value']]);
        });

        base64($request->image) ? $product->addMediaFromBase64($request->image)->toMediaCollection('main')
            : $product->addMedia($request->image)->toMediaCollection('main');

        ApiService::_success($product);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $product = $this->productRepo->find($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
