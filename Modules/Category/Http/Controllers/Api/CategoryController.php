<?php

namespace Modules\Category\Http\Controllers\Api;

use App\Http\Resources\CategoryCollection;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Repository\CategoryRepositoryInterface;

class CategoryController extends Controller
{

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }


    public function index()
    {
        $categories = $this->categoryRepo->all();
        // ApiService::_success($categories);
        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'link' => ['nullable'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'parent_id' => $request->parent_id,
        ];
        $category = $this->categoryRepo->create($data);

        $request->image ?  $category->addMedia($request->image)->toMediaCollection() : '';
        ApiService::_success($category);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepo->show($id);
        ApiService::_success($category);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        ApiService::Validator($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'link' => ['nullable'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'parent_id' => $request->parent_id,
        ];
        $this->categoryRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $category = $this->categoryRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
