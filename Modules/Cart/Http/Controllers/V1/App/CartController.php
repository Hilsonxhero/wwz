<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\Cart\Http\Requests\App\CartRequest;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Transformers\ProductResource;
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

    /**
     * Show the all specified resource.
     * @param int $id
     * @return ProductResource
     */

    public function index(Request $request)
    {
        $dd = $cart = Cart::content();
        // return $dd;
        // return Cart::content()->items;
        return new CartResource($cart);
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
            $variant->calculate_discount,
            $variant->price,
            $variant->weight,
            1
        );


        $cart = Cart::content();


        return new CartResource($cart);
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
        Cart::update($id, ['quantity' => $request->quantity]);
        $cart = Cart::content();
        return new CartResource($cart);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Cart::remove($id);
        $cart = Cart::content();
        return new CartResource($cart);
    }
}
