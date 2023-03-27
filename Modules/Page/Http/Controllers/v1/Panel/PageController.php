<?php

namespace Modules\Page\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Page\Http\Requests\PageRequest;
use Modules\Page\Repository\PageRepositoryInterface;
use Modules\Page\Transformers\PageResource;

class PageController extends Controller
{
    private $pageRepo;
    public function __construct(PageRepositoryInterface $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pages = $this->pageRepo->all();
        return PageResource::collection($pages);
        // ApiService::_success($pages);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PageRequest $request)
    {
        $data = [
            'title' => $request->title,
            'title_en' => $request->title_en,
            'content' => $request->content,
        ];
        $page = $this->pageRepo->create($data);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $page = $this->pageRepo->show($id);
        ApiService::_success($page);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(PageRequest $request, $id)
    {
        $data = [
            'title' => $request->title,
            'title_en' => $request->title_en,
            'content' => $request->content,
        ];
        $page = $this->pageRepo->update($id, $data);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $page = $this->pageRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
