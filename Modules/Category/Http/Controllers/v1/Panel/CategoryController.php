<?php

namespace Modules\Category\Http\Controllers\v1\Panel;


use Illuminate\Http\File;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;
use Modules\Category\Transformers\CategoryCollection;
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

    public function select(Request $request)
    {
        $categories = $this->categoryRepo->select($request->q);
        ApiService::_success($categories);
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
            'title_en' => ['required'],
            'description' => ['required'],
            'link' => ['nullable'],
            'parent' => ['nullable', 'exists:categories,id'],
        ]);

        $data = [
            'title' => $request->title,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'link' => $request->link,
            'parent_id' => $request->parent,
            'status' => "enable"
        ];
        $category = $this->categoryRepo->create($data);


        base64($request->image) ? $category->addMediaFromBase64($request->image)->toMediaCollection()
            : $category->addMedia($request->image)->toMediaCollection();

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
        // ApiService::_success($category);
        return new CategoryResource($category);
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
            'title_en' => ['required'],
            'description' => ['required'],
            'link' => ['nullable'],
            'parent' => ['nullable', 'exists:categories,id'],
        ]);
        $data = [
            'title' => $request->title,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'link' => $request->link,
            'parent_id' => $request->parent,
        ];
        $category = $this->categoryRepo->update($id, $data);

        if ($request->image) {
            // $category->last()->delete();
            $category->clearMediaCollectionExcept();
            $category->addMediaFromBase64($request->image)->toMediaCollection();
        }


        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->categoryRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
