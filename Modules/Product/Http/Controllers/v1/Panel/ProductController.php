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
        // ApiService::_response($request->filled('shipment_id'), 403);

        ApiService::Validator($request->all(), [
            'title_fa' => ['required', 'min:4'],
            'title_en' => ['required', 'min:4'],
            'review' => ['required', 'min:4'],
            'images' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'warranty_id' => ['required', 'exists:warranties,id'],
            'shipment_id' => ['required', 'exists:shipments,id'],
            'status' => ['required', Rule::in(Product::$statuses)],
            // "features" => ['required'],
            // "features.*.name" => ['required', 'exists:features,id'],
            // "features.*.value" => ['required', 'exists:feature_values,id']
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
        $variants = collect(json_decode($request->input('variants')));
        $images = collect(json_decode($request->input('images')));
        // ApiService::_response($variants, 403);

        $variants->each(function ($variant) use ($product, $request) {

            $producy_variant = $product->variants()->create([
                'warranty_id' => $request->warranty_id,
                'shipment_id' => $request->shipment_id,
                'price' => $variant->price,
                'discount' => $variant->discount,
                'discount_price' => $variant->discount_price,
                'stock' => $variant->stock,
                'weight' => $request->weight,
                'order_limit' => $variant->order_limit,
                'default_on' => 1,
            ]);

            foreach ($variant->variants as $combination) {
                $producy_variant->combinations()->create([
                    'variant_id' => $combination->id,
                ]);
            }
        });

        // $features = collect(json_decode($request->input('features')));

        // $features->each(function ($attr) use ($product) {
        //     foreach ($attr->values as $value) {
        //         $product->features()->attach($attr->feature, ['feature_value_id' => $value]);
        //     }
        // });

        $main_image = $images->first();

        base64($main_image->file) ? $product->addMediaFromBase64($main_image->file)->toMediaCollection('main')
            : $product->addMedia($main_image->file)->toMediaCollection('main');

        $images->each(function ($image) use ($product) {
            base64($image->file) ? $product->addMediaFromBase64($image->file)->toMediaCollection('thumbs')
                : $product->addMedia($image->file)->toMediaCollection('thumbs');
        });

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
        $product = $this->productRepo->delete($id);

        ApiService::_success(trans('response.responses.200'));
    }
}
