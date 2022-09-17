<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Services\Cart\Facades\Cart;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductVariantRepositoryInterface;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Transformers\VariantResource;

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

    public function get()
    {
        ApiService::_success("hello cart");
    }

    public function add(Request $request)
    {
        $variant = $this->variantRepo->find($request->variant_id);
        $product = $this->productRepo->find($variant->product_id);

        if (Cart::has($variant->id)) {
            Cart::update($variant->id, 1);
        } else {
            Cart::put([
                'variant' => new ProductVariantResource($variant),
                'product' => new ProductResource($product),
                'quantity' => 1,
            ], []);
        };
        $cart =  Cart::all();

        // ApiService::_success($cart);
        return new CartResource($cart);
        // return  CartItemsResource::collection($cart);
    }
}
