<?php

namespace Modules\Comment\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Comment\Enums\CommentStatus;
use Modules\Comment\Http\Requests\App\CommentRequest;
use Modules\Comment\Repository\CommentRepositoryInterface;
use Modules\Comment\Transformers\Panel\CommentResource;
use Modules\Product\Repository\ProductRepositoryInterface;

class CommentController extends Controller
{
    private $commentRepo;
    private $productRepo;

    public function __construct(CommentRepositoryInterface $commentRepo, ProductRepositoryInterface $productRepo)
    {
        $this->commentRepo = $commentRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $states = $this->commentRepo->all();
        return CommentResource::collection($states);
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

        $this->commentRepo->create($product, $data);

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
