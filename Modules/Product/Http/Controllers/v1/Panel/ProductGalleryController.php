<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Product\Http\Requests\Panel\ProductGalleryRequest;
use Modules\Product\Http\Requests\Panel\ProductQuestionRequest;
use Modules\Product\Repository\ProductGalleryRepositoryInterface;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\Panel\ProductGalleryResource;



class ProductGalleryController extends Controller
{
    private $galleryRepo;
    private $productRepo;


    public function __construct(
        ProductGalleryRepositoryInterface $galleryRepo,
        ProductRepositoryInterface $productRepo,
    ) {
        $this->galleryRepo = $galleryRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $questions = $this->galleryRepo->all();
        return ProductGalleryResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductGalleryRequest $request)
    {
        // if (base64($request->media)) {
        //     ApiService::_throw("hereeee");
        // }

        $data = [
            'title' => $request->title,
            'product_id' => $request->product_id,
            'mime_type' => base64($request->media) ? explode('/', mime_content_type($request->media))[1] :  strtolower($request->media->getClientMimeType()),
        ];

        $product_gallery =  $this->galleryRepo->create($data);

        base64($request->media) ? $product_gallery->addMediaFromBase64($request->media)->toMediaCollection('main')
            : $product_gallery->addMedia($request->media)->toMediaCollection('main');

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($product, $id)
    {
        $state = $this->galleryRepo->show($id);
        return new ProductGalleryResource($state);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductGalleryRequest $request, $product, $id)
    {
        $data = [
            'title' => $request->title,
            'product_id' => $request->product_id,
            'mime_type' =>  strtolower($request->media->getClientMimeType()),
        ];

        $product_gallery = $this->galleryRepo->update($id, $data);

        if ($request->media) {
            $product_gallery->clearMediaCollectionExcept('main');
            base64(json_decode($request->media)) ? $product_gallery->addMediaFromBase64(json_decode($request->media))->toMediaCollection('main')
                : $product_gallery->addMedia($request->media)->toMediaCollection('main');
        }

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($product, $id)
    {
        $state = $this->galleryRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
