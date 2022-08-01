<?php

namespace Modules\Shipment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Brand\Transformers\BrandResource;
use Modules\Shipment\Repository\ShipmentRepositoryInterface;
use Modules\Shipment\Transformers\ShipmentCollectionResource;

class ShipmentController extends Controller
{
    /**
     * @var ShipmentRepositoryInterface
     */
    private $brandRepo;

    public function __construct(ShipmentRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }


    public function index()
    {
        $categories = $this->brandRepo->all();
        // ApiService::_success($categories);
        return new ShipmentCollectionResource($categories);
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
        $shipment = $this->brandRepo->create($data);

        ApiService::_success($shipment);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $shipment = $this->brandRepo->show($id);
        return new BrandResource($shipment);
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

        $shipment =  $this->brandRepo->update($id, $data);

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
