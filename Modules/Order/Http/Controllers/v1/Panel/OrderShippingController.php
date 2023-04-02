<?php

namespace Modules\Order\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Order\Repository\OrderRepositoryInterface;
use Modules\Order\Repository\OrderShippingRepositoryInterface;
use Modules\Order\Transformers\Panel\OrderResource;

class OrderShippingController extends Controller
{
    private $orderRepo;
    private $orderShippingRepo;
    public function __construct(OrderRepositoryInterface $orderRepo, OrderShippingRepositoryInterface $orderShippingRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->orderShippingRepo = $orderShippingRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $orders = $this->orderRepo->all();
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $order = $this->orderRepo->show($id);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $data = [
            'status' => $request->status
        ];
        $order = $this->orderShippingRepo->update($id, $data);
        return  ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
