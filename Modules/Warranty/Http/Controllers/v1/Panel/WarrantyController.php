<?php

namespace Modules\Warranty\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Warranty\Repository\WarrantyRepositoryInterface;
use Modules\Warranty\Transformers\WarrantyResource;
use Modules\Warranty\Transformers\WarrantyResourceCollection;

class WarrantyController extends Controller
{
    /**
     * @var WarrantyRepositoryInterface
     */
    private $warrantyRepo;

    public function __construct(WarrantyRepositoryInterface $warrantyRepo)
    {
        $this->warrantyRepo = $warrantyRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $warranties = $this->warrantyRepo->all();
        return new WarrantyResourceCollection($warranties);
        // ApiService::_success($warranties);
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
        ]);

        $data = [
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
        ];
        $warranty = $this->warrantyRepo->create($data);

        ApiService::_success($warranty);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $warranty = $this->warrantyRepo->show($id);
        return new WarrantyResource($warranty);
        // ApiService::_success($warranty);
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
        ]);

        $data = [
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
        ];

        $warranty = $this->warrantyRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->warrantyRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}