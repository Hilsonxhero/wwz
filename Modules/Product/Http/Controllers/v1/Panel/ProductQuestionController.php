<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Comment\Transformers\App\CommentResource;
use Modules\Product\Enums\ProductQuestionStatusStatus;
use Modules\Product\Http\Requests\Panel\ProductQuestionRequest;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\Panel\ProductQuestionResource;
use Modules\Product\Repository\ProductQuestionRepositoryInterface;

class ProductQuestionController extends Controller
{
    private $questionRepo;
    private $productRepo;


    public function __construct(
        ProductQuestionRepositoryInterface $questionRepo,
        ProductRepositoryInterface $productRepo,
    ) {
        $this->questionRepo = $questionRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $questions = $this->questionRepo->all();
        return ProductQuestionResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductQuestionRequest $request, $id)
    {
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $state = $this->questionRepo->show($id);
        return new ProductQuestionResource($state);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductQuestionRequest $request, $id)
    {
        $data = [
            'content' => $request->content,
            'status' => $request->status,
        ];

        $this->questionRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $state = $this->questionRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
