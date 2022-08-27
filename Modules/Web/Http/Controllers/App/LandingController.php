<?php

namespace Modules\Web\Http\Controllers\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\IncredibleProductResource;
use Modules\Product\Repository\IncredibleProductRepositoryInterface;

class LandingController extends Controller
{
    private $productRepo;
    private $IncredibleProductRepo;


    public function __construct(ProductRepositoryInterface $productRepo, IncredibleProductRepositoryInterface $IncredibleProductRepo)
    {
        $this->productRepo = $productRepo;
        $this->IncredibleProductRepo = $IncredibleProductRepo;
    }

    public function index()
    {
        $incredible_products = $this->IncredibleProductRepo->take();
        $data = [
            'incredible_products' => IncredibleProductResource::collection($incredible_products)
        ];

        // return IncredibleProductResource::collection($incredible_products);

        ApiService::_success($data);
    }
}
