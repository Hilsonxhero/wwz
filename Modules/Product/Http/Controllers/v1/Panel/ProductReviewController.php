<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\Panel\ProductReviewRequest;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\ProductReviewRepositoryInterface;
use Modules\Product\Transformers\Panel\ProductReviewResource;

class ProductReviewController extends Controller
{
    private $reviewRepo;
    private $productRepo;


    public function __construct(
        ProductReviewRepositoryInterface $reviewRepo,
        ProductRepositoryInterface $productRepo,
    ) {
        $this->reviewRepo = $reviewRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $questions = $this->reviewRepo->all();
        return ProductReviewResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductReviewRequest $request)
    {
        // if (base64($request->media)) {
        //     ApiService::_throw("hereeee");
        // }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'product_id' => $request->product_id,
        ];

        $review =  $this->reviewRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($product, $id)
    {
        $state = $this->reviewRepo->show($id);
        return new ProductReviewResource($state);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductReviewRequest $request, $product, $id)
    {
        $data = [
            'title' => $request->title,
            'product_id' => $request->product_id,
            'content' => $request->content,
        ];

        $review = $this->reviewRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($product, $id)
    {
        $review = $this->reviewRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
