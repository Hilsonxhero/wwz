<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Http\Requests\App\CartRequest;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\VariantResource;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductVariantRepositoryInterface;

class CartController extends Controller
{
    private $variantRepo;
    private $productRepo;
    public function __construct(
        ProductVariantRepositoryInterface $variantRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->variantRepo = $variantRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Show the all specified resource.
     * @param int $id
     * @return ProductResource
     */

    public function index(Request $request)
    {
        $cart = Cart::content();

        foreach ($cart as $key => $value) {
            $variant = $this->variantRepo->find($value->variant);
            $cart =  Cart::update($value->rowId, ['discount' => $variant->calculate_discount, 'price' => $variant->price]);
        }

        $content = (object) [
            'items' => $cart,
            'items_count' => $cart->count(),
            'payable_price' => Cart::subtotal(),
            'rrp_price' => Cart::total(),
        ];

        return new CartResource($content);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */

    public function store(CartRequest $request)
    {
        $variant = $this->variantRepo->find($request->variant_id);
        $product = $this->productRepo->find($variant->product_id);

        Cart::add(
            $variant->id,
            $product->id,
            $variant->id,
            $variant->discount,
            $variant->price,
            $variant->weight,
            1
        );

        $cart = Cart::content();
        $content = (object) [
            'items' => $cart,
            'items_count' => $cart->count(),
            'payable_price' => Cart::subtotal(),
            'rrp_price' => Cart::total(),
        ];
        return new CartResource($content);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return ProductResource
     */

    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */

    public function update(CartRequest $request, $id)
    {
        $cart = Cart::update($id, ['qty' => $request->quantity]);
        $content = (object) [
            'items' => $cart,
            'items_count' => $cart->count(),
            'payable_price' => Cart::subtotal(),
            'rrp_price' => Cart::total(),
        ];
        return new CartResource($content);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
    }
}
