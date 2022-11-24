<?php

namespace Modules\Payment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Requests\Panel\PaymentMethodRequest;
use Modules\Payment\Repository\PaymentMethodRepositoryInterface;
use Modules\Payment\Transformers\Panel\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    private $paymentMethodRepo;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepo)
    {
        $this->paymentMethodRepo = $paymentMethodRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $methods = $this->paymentMethodRepo->all();
        return  PaymentMethodResource::collection($methods);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PaymentMethodRequest $request)
    {
        $data = array(
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'is_default' => $request->input('is_default'),
        );

        $method = $this->paymentMethodRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $method = $this->paymentMethodRepo->show($id);
        return new PaymentMethodResource($method);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(PaymentMethodRequest $request, $id)
    {
        $data = array(
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'is_default' => $request->input('is_default'),
        );

        $method = $this->paymentMethodRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $method = $this->paymentMethodRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
