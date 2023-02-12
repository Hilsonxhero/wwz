<?php

namespace Modules\Comment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Comment\Http\Requests\Panel\CommentRequest;
use Modules\Comment\Repository\CommentRepositoryInterface;
use Modules\Comment\Transformers\Panel\CommentResource;

class CommentController extends Controller
{
    private $commentRepo;

    public function __construct(CommentRepositoryInterface $commentRepo)
    {
        $this->commentRepo = $commentRepo;
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
    public function store(Request $request)
    {
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

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
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
