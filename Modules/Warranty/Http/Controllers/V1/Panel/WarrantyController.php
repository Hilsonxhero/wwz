<?php

namespace Modules\Warranty\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Warranty\Repository\WarrantyRepositoryInterface;

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
        ApiService::_success($warranties);
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
            'description' => $request->description,
        ];
        $shipment = $this->warrantyRepo->create($data);

        ApiService::_success($shipment);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $shipment = $this->warrantyRepo->show($id);
        ApiService::_success($shipment);
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
            'description' => $request->description,
        ];

        $shipment =  $this->warrantyRepo->update($id, $data);

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
