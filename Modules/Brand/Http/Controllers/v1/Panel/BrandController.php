<?php

namespace Modules\Brand\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Repository\BrandRepositoryInterface;
use Modules\Brand\Transformers\BrandCollectionResource;
use Modules\Brand\Transformers\BrandResource;

class BrandController extends Controller
{

    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepo;

    public function __construct(BrandRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }
    public function index()
    {
        $brands = $this->brandRepo->all();
        return new BrandCollectionResource($brands);
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
            'image' => ['required'],
            'is_special' => ['required', 'boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $data = [
            'title' => $request->title,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'link' => $request->link,
            'category_id' => $request->category_id,
            'is_special' =>  $request->is_special
        ];
        $brand = $this->brandRepo->create($data);

        base64($request->image) ? $brand->addMediaFromBase64($request->image)->toMediaCollection()
            : $brand->addMedia($request->image)->toMediaCollection();
        ApiService::_success($brand);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $brand = $this->brandRepo->show($id);
        // ApiService::_success($brand);
        return new BrandResource($brand);
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
            'status' => ['nullable', Rule::in(Brand::$statuses)],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);
        $data = [
            'title' => $request->title,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'link' => $request->link,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'is_special' =>  $request->is_special
        ];
        $brand =  $this->brandRepo->update($id, $data);

        if ($request->image) {
            // $brand->last()->delete();
            $brand->clearMediaCollectionExcept();
            base64($request->image) ? $brand->addMediaFromBase64($request->image)->toMediaCollection()
                : $brand->addMedia($request->image)->toMediaCollection();
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
        $this->brandRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
