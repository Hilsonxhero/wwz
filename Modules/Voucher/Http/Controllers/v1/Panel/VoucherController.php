<?php

namespace Modules\Voucher\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Voucher\Http\Requests\Panel\VoucherRequest;
use Modules\Voucher\Repository\VoucherRepositoryInterface;
use Modules\Voucher\Transformers\Panel\VoucherResource;

class VoucherController extends Controller
{

    private $voucherRepo;

    public function __construct(VoucherRepositoryInterface $voucherRepo)
    {
        $this->voucherRepo = $voucherRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $vouchers = $this->voucherRepo->all();
        return VoucherResource::collection($vouchers);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(VoucherRequest $request)
    {

        $data = array(
            'code' => $request->code,
            'value' => $request->value,
            'minimum_spend' => $request->minimum_spend,
            'maximum_spend' => $request->maximum_spend,
            'usage_limit_per_voucher' => $request->usage_limit_per_voucher,
            'usage_limit_per_customer' => $request->usage_limit_per_customer,
            'is_percent' => $request->is_percent,
            'is_active' => $request->is_active,
            'start_date' => createDatetimeFromFormat($request->start_date),
            'end_date' => createDatetimeFromFormat($request->end_date),
        );

        $voucher = $this->voucherRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $voucher = $this->voucherRepo->show($id);
        return new VoucherResource($voucher);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(VoucherRequest $request, $id)
    {
        $data = array(
            'code' => $request->code,
            'value' => $request->value,
            'minimum_spend' => $request->minimum_spend,
            'maximum_spend' => $request->maximum_spend,
            'usage_limit_per_voucher' => $request->usage_limit_per_voucher,
            'usage_limit_per_customer' => $request->usage_limit_per_customer,
            'is_percent' => $request->is_percent,
            'is_active' => $request->is_active,
            'start_date' => createDatetimeFromFormat($request->start_date),
            'end_date' => createDatetimeFromFormat($request->end_date),
        );

        $voucher = $this->voucherRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $voucher = $this->voucherRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
