<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Cart\Services\Cart\Facades\Cart;
use Modules\Product\Repository\ProductVariantRepositoryInterface;

class CartController extends Controller
{
    private $variantRepo;
    public function __construct(ProductVariantRepositoryInterface $variantRepo)
    {
        $this->variantRepo = $variantRepo;
    }

    public function get()
    {
        ApiService::_success("hello cart");
    }

    public function add(Request $request)
    {
        $product = $this->variantRepo->find($request->variant_id)->product;

        if (!Cart::has($product)) {
            Cart::put([
                'quantity' => 1,
                'price' => 43223,
            ], $product);
        };
    }
}
