<?php

namespace Modules\Voucher\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Modules\Voucher\Http\Requests\Panel\VoucherableRequest;
use Modules\Voucher\Http\Requests\Panel\VoucherRequest;
use Modules\Voucher\Repository\VoucherableRepositoryInterface;
use Modules\Voucher\Repository\VoucherRepositoryInterface;
use Modules\Voucher\Transformers\Panel\VoucherResource;

class VoucherableController extends Controller
{

    private $voucherableRepo;

    public function __construct(VoucherableRepositoryInterface $voucherableRepo)
    {
        $this->voucherableRepo = $voucherableRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $vouchers = $this->voucherableRepo->all();
        return VoucherResource::collection($vouchers);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(VoucherableRequest $request)
    {

        $data = array(
            // 'voucher_id' => $request->voucher,
            // 'voucherable_id' => $request->voucherable_id,
            // 'voucherable_type' => $request->voucherable_type,
        );

        if ($request->filled('category')) {
            array_push($data, array(
                'voucher_id' => $request->voucher,
                'voucherable_id' => $request->category,
                'voucherable_type' => get_class(Category::query()->whereId($request->category)->first()),
            ));
        }


        if ($request->filled('product')) {
            array_push($data, array(
                'voucher_id' => $request->voucher,
                'voucherable_id' => $request->product,
                'voucherable_type' => get_class(Product::query()->whereId($request->product)->first()),
            ));
        }

        if ($request->filled('user')) {
            array_push($data, array(
                'voucher_id' => $request->voucher,
                'voucherable_id' => $request->user,
                'voucherable_type' => get_class(User::query()->whereId($request->user)->first()),
            ));
        }

        $voucher = $this->voucherableRepo->createMany($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $voucher = $this->voucherableRepo->show($id);
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

        $voucher = $this->voucherableRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $voucher = $this->voucherableRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
