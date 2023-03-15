<?php

namespace Modules\Order\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\User\Repository\UserRepository;
use Modules\Order\Transformers\App\OrderResource;
use Modules\Order\Repository\OrderRepositoryInterface;

class OrderController extends Controller
{
    private $orderRepo;
    private $userRepo;

    public function __construct(OrderRepositoryInterface $orderRepo, UserRepository $userRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        $orders = $this->userRepo->orders($user);
        $order_collection =  OrderResource::collection($orders);
        ApiService::_success(array(
            'orders' => $order_collection->items(),
            'pager' => array(
                'pages' => $order_collection->lastPage(),
                'total' => $order_collection->total(),
                'current_Page' => $order_collection->currentPage(),
                'per_page' => $order_collection->perPage(),
            ),
        ));
    }


    public function tabs()
    {
        $user = auth()->user();

        $tabs = $this->orderRepo->tabs($user);

        ApiService::_success(array(
            'tabs' => $tabs,
        ));
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
        $order = $this->orderRepo->update($id, $data);
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
