<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductVariantRepositoryInterface;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Transformers\ProductVariantResourceCollection;

class ProductVariantController extends Controller
{

    private $productRepo;
    private $variantRepo;

    public function __construct(ProductRepositoryInterface $productRepo, ProductVariantRepositoryInterface $variantRepo)
    {
        $this->productRepo = $productRepo;
        $this->variantRepo = $variantRepo;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($id)
    {
        $variants = $this->productRepo->variants($id);
        return ProductVariantResource::collection($variants);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        $product = $this->productRepo->find($id);
        $variant = $product->variants()->create([
            'warranty_id' => $request->warranty,
            'shipment_id' => $request->shipment,
            'price' => $request->price,
            'discount' => $request->discount,
            'discount_price' => $request->price * $request->discount / 100,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'order_limit' => $request->order_limit,
            'default_on' => 1,
            'discount_expire_at' => \Morilog\Jalali\CalendarUtils::createDatetimeFromFormat('Y/m/d H:i', $request->discount_expire_at)
        ]);

        $data = [];
        $combinations = json_decode($request->input('combinations'));

        foreach ($combinations as $key => $combination) {
            $data[$key] = [
                'product_variant_id' => $variant->id,
                'variant_id' => $combination->id
            ];
        }

        $variant->combinations()->insert($data);

//        ApiService::_response($product, 403);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
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
    public function destroy($product, $variant)
    {
        $variant = $this->variantRepo->delete($variant);
        ApiService::_success(trans('response.responses.200'));
    }
}
