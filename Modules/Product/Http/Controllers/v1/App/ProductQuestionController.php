<?php

namespace Modules\Product\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Comment\Http\Requests\App\CommentRequest;
use Modules\Comment\Transformers\App\CommentResource;
use Modules\Product\Enums\ProductQuestionStatusStatus;
use Modules\Product\Http\Requests\App\ProductQuestionRequest;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\App\ProductQuestionResource;
use Modules\Product\Repository\ProductQuestionRepositoryInterface;

class ProductQuestionController extends Controller
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
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $questions = $this->productRepo->questions($id);

        $questions_collection =  ProductQuestionResource::collection($questions);

        $data = [
            'questions' => $questions_collection->items(),
            'pager' => array(
                'pages' => $questions_collection->lastPage(),
                'total' => $questions_collection->total(),
                'current_Page' => $questions_collection->currentPage(),
                'per_page' => $questions_collection->perPage(),

            ),
        ];

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductQuestionRequest $request, $id)
    {
        $user = auth()->user();

        $product = $this->productRepo->find($id);

        $data = array(
            'content' => $request->content,
            'product_question_id' => $request->product_question_id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'is_anonymous' => $request->is_anonymous,
            'is_buyer' => false,
            'status' => ProductQuestionStatusStatus::Pending
        );

        $question =  $this->questionRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $state = $this->questionRepo->show($id);
        return new CommentResource($state);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductQuestionRequest $request, $id)
    {
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
