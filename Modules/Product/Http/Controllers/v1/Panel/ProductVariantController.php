<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Http\Requests\Panel\ProductVariantRequest;
use Modules\Product\Repository\ProductVariantRepositoryInterface;
use Modules\Seller\Repository\SellerRepositoryInterface;

class ProductVariantController extends Controller
{

    private $productRepo;
    private $variantRepo;
    private $sellerRepo;

    public function __construct(
        ProductRepositoryInterface $productRepo,
        ProductVariantRepositoryInterface $variantRepo,
        SellerRepositoryInterface $sellerRepo
    ) {
        $this->productRepo = $productRepo;
        $this->variantRepo = $variantRepo;
        $this->sellerRepo = $sellerRepo;
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
    public function store(ProductVariantRequest $request, $id)
    {
        $product = $this->productRepo->find($id);

        $variants_ids = $request->input('combinations');

        $combinations = $product->combinations;

        if (count($combinations) >= 1) {

            $groupd_combinations = $combinations->mapToGroups(function ($ss) {
                return [$ss['variant']['product_variant_id'] => $ss['variant']['id']];
            });

            $match = $groupd_combinations->every(function ($groupd_combination, $key) use ($variants_ids) {
                $intersect_combinations = collect($groupd_combination)->intersect($variants_ids);
                return  count($intersect_combinations) >= count($variants_ids) && count($groupd_combination) == count($variants_ids);
            });

            if ($match) {
                ApiService::_throw(array('message' => trans('response.variant_exists'), 'staus' => 410));
            }
        }

        $default_seller = $this->sellerRepo->default();

        $variant_data = [
            'product_id' => $product->id,
            'seller_id' => $default_seller->id,
            'warranty_id' => $request->warranty_id,
            'shipment_id' => $request->shipment_id,
            'price' => $request->price,
            'discount' => $request->discount,
            'discount_price' => $request->price * $request->discount / 100,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'order_limit' => $request->order_limit,
            'default_on' => 1,
            'discount_expire_at' => $request->discount_expire_at ? createDatetimeFromFormat($request->discount_expire_at) : null
        ];

        $variant = $this->variantRepo->create($variant_data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($product, $id)
    {
        $variant = new ProductVariantResource($this->variantRepo->find($id));
        ApiService::_success($variant);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductVariantRequest $request, $product, $id)
    {
        $product = $this->productRepo->find($product);

        $variant_data = [
            'warranty_id' => $request->warranty_id,
            'shipment_id' => $request->shipment_id,
            'price' => $request->price,
            'discount' => $request->discount,
            'discount_price' => $request->price * $request->discount / 100,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'order_limit' => $request->order_limit,
            'default_on' => 1,
            'discount_expire_at' => $request->discount_expire_at ? createDatetimeFromFormat($request->discount_expire_at) : null
        ];

        $variant = $this->variantRepo->update($id, $variant_data);

        ApiService::_success(trans('response.responses.200'));
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
