<?php

namespace Modules\Category\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Http\Requests\CategoryBannerRequest;
use Modules\Category\Http\Requests\CategorySlide;
use Modules\Category\Repository\CategoryBannerRepositoryInterface;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Repository\CategorySlideRepositoryInterface;
use Modules\Category\Transformers\CategoryBannerResource;

class CategoryBannerController extends Controller
{
    private $bannerRepo;
    private $categoryRepo;

    public function __construct(CategoryBannerRepositoryInterface $bannerRepo, CategoryRepositoryInterface $categoryRepo)
    {
        $this->bannerRepo = $bannerRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $slides = $this->bannerRepo->all();
        return CategoryBannerResource::collection($slides);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CategoryBannerRequest $request)
    {
        $category = $this->categoryRepo->find($request->category);

        $slide = $this->bannerRepo->create($category, $request);

        base64($request->banner) ? $slide->addMediaFromBase64($request->banner)->toMediaCollection()
            : $slide->addMedia($request->banner)->toMediaCollection();


        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return CategoryBannerResource
     */
    public function show($id)
    {
        $slide = $this->bannerRepo->show($id);
        return new CategoryBannerResource($slide);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CategoryBannerRequest $request, $id)
    {
        $slide = $this->bannerRepo->update($id, $request);


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
        $slide = $this->bannerRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}