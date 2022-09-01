<?php

namespace Modules\Article\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\ArticleRequest;
use Modules\Article\Repository\ArticleRepositoryInterface;
use Modules\Article\Transformers\ArticleResource;

class ArticleController extends Controller
{
    private $articleRepo;

    public function __construct(ArticleRepositoryInterface $articleRepo)
    {
        $this->articleRepo = $articleRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $articles = $this->articleRepo->all();
        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ArticleRequest $request)
    {
        $article = $this->articleRepo->create($request);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $article = $this->articleRepo->show($id);
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ArticleRequest $request, $id)
    {
        $article = $this->articleRepo->update($id, $request);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $article = $this->articleRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
