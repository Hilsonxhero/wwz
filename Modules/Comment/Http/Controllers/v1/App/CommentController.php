<?php

namespace Modules\Comment\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Comment\Enums\CommentStatus;
use Illuminate\Pagination\AbstractPaginator;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Http\Requests\App\CommentRequest;
use Modules\Comment\Transformers\App\CommentResource;
use Modules\Comment\Transformers\App\ScoreModelResource;
use Modules\Comment\Repository\CommentRepositoryInterface;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Comment\Repository\ScoreModelRepositoryInterface;
use Modules\Product\Entities\Product;

class CommentController extends Controller
{
    private $commentRepo;
    private $productRepo;
    private $scoreModelRepo;

    public function __construct(
        CommentRepositoryInterface $commentRepo,
        ProductRepositoryInterface $productRepo,
        ScoreModelRepositoryInterface $scoreModelRepo
    ) {
        $this->commentRepo = $commentRepo;
        $this->productRepo = $productRepo;
        $this->scoreModelRepo = $scoreModelRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $comments = $this->productRepo->comments($id);

        $comments_collection =  CommentResource::collection($comments);

        $data = [
            'comments' => $comments_collection->items(),
            'models' => ScoreModelResource::collection($this->scoreModelRepo->get()),
            'scores' => ScoreModelResource::collection($this->commentRepo->scores($id)),
            'pager' => array(
                'pages' => $comments_collection->lastPage(),
                'total' => $comments_collection->total(),
                'current_Page' => $comments_collection->currentPage(),
                'per_page' => $comments_collection->perPage(),

            ),
        ];

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CommentRequest $request, $id)
    {
        $user = auth()->user();

        $product = $this->productRepo->find($id);

        $data = array(
            'title' => $request->title,
            'body' => $request->content,
            'comment_id' => $request->comment_id,
            'user_id' => $user->id,
            'is_anonymous' => $request->is_anonymous,
            'is_recommendation' => $request->is_recommendation,
            'is_buyer' => false,
            'advantages' => json_decode(json_encode($request->advantages)),
            'disadvantages' => json_decode(json_encode($request->disadvantages)),
            'status' => CommentStatus::Pending
        );

        $comment =  $this->commentRepo->create($product, $data);

        if ($request->filled('scores')) {
            $scores =  collect($request->input('scores'));

            $data = collect([]);

            $scores->each(function ($score, $i) use ($data) {
                $data->push(array("value" => $score['value'], "score_model_id" => $score['id']));
            });

            $comment->scores()->createMany($data);
        }

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $state = $this->commentRepo->show($id);
        return new CommentResource($state);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CommentRequest $request, $id)
    {
        ApiService::Validator($request->all(), [
            'name' => ['required'],
            'zone_code' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'zone_code' => $request->zone_code,
        ];

        $this->commentRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $state = $this->commentRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
