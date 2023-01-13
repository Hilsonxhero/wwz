<?php

namespace Modules\Category\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategorySlide;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Repository\CategorySlideRepositoryInterface;
use Modules\Category\Transformers\CategorySlideResource;
use Modules\Slide\Entities\Slide;

class CategorySlideController extends Controller
{
    private $slideRepo;
    private $categoryRepo;

    public function __construct(CategorySlideRepositoryInterface $slideRepo, CategoryRepositoryInterface $categoryRepo)
    {
        $this->slideRepo = $slideRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $slides = $this->slideRepo->all();
        return CategorySlideResource::collection($slides);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CategorySlide $request)
    {
        $category = $this->categoryRepo->find($request->category);

        $slide = $this->slideRepo->create($category, $request);

        base64($request->banner) ? $slide->addMediaFromBase64($request->banner)->toMediaCollection()
            : $slide->addMedia($request->banner)->toMediaCollection();


        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return CategorySlideResource
     */
    public function show($id)
    {
        $slide = $this->slideRepo->show($id);
        return new CategorySlideResource($slide);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CategorySlide $request, $id)
    {
        $slide = $this->slideRepo->update($id, $request);


        if ($request->filled('banner')) {
            $slide->clearMediaCollectionExcept();
            base64($request->banner) ? $slide->addMediaFromBase64($request->banner)->toMediaCollection()
                : $slide->addMedia($request->banner)->toMediaCollection();
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
        $slide = $this->slideRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}