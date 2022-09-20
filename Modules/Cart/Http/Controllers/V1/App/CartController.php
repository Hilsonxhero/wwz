<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\VariantResource;
use Modules\Cart\Transformers\App\CartItemsResource;
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

    public function get()
    {
        $cart = Cart::content();
        $content = (object) [
            'items' => $cart,
            'items_count' => $cart->count(),
            'payable_price' => Cart::subtotal(),
            'rrp_price' => Cart::total(),
        ];
        // ApiService::_success($cart);
        return new CartResource($content);
    }

    public function add(Request $request)
    {
        $variant = $this->variantRepo->find($request->variant_id);
        $product = $this->productRepo->find($variant->product_id);

        Cart::add(
            $variant->id,
            new ProductResource($product),
            new ProductVariantResource($variant),
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
        // ApiService::_success($cart);
        return new CartResource($content);
    }
}
