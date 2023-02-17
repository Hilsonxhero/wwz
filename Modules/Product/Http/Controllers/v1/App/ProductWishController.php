<?php

namespace Modules\Product\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Enums\ProductQuestionStatusStatus;
use Modules\Product\Http\Requests\App\ProductQuestionRequest;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductQuestionRepositoryInterface;

class ProductWishController extends Controller
{
    private $questionRepo;
    private $productRepo;


    public function __construct(
        ProductQuestionRepositoryInterface $questionRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->questionRepo = $questionRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        $user = auth()->user();

        $product = $this->productRepo->find($id);

        $product->wishes()->sync(
            $user->id,
            false
        );

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $product = $this->productRepo->find($id);

        $product->wishes()->detach(
            $user->id
        );

        ApiService::_success(trans('response.responses.200'));
    }
}
