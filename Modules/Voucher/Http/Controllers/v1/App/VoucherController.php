<?php

namespace Modules\Voucher\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\Cart\Entities\Cart as EntitiesCart;
use Modules\Voucher\Entities\Voucherable;
use Modules\Voucher\Http\Requests\Panel\VoucherRequest;
use Modules\Voucher\Transformers\Panel\VoucherResource;
use Modules\Voucher\Repository\VoucherRepositoryInterface;
use Modules\Voucher\Transformers\Panel\VoucherableResource;
use Modules\Voucher\Http\Requests\App\VoucherRequest as AppVoucherRequest;

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
    public function check(AppVoucherRequest $request)
    {
        $cart = Cart::content();

        // return $cart;

        $voucher = $this->voucherRepo->check($request->code);

        if (!$voucher) {
            ApiService::_throw("کد تخفیف معتبر نمی باشد", 400);
        }

        $ww = EntitiesCart::query()->where('id', $cart->id)->first();

        // Voucherable::query()->create([
        //     'voucher_id' => 5,
        //     'voucherable_id' => $cart->id,
        //     'voucherable_type' => "Modules\Cart\Entities\Cart",
        // ]);

        return  $ww->voucher;



        if ($voucher->is_percent) {
            $discount = $cart->payable_price * ($voucher->value / 100);
        } else {
            $discount = $voucher->value;
        }

        $data = array(
            "voucher_code" => $voucher->code,
            "voucher_discount" => $discount,
            "items_discount" => $cart->items_discount,
            "rrp_price" => $cart->rrp_price,
            "payable_price" => $cart->payable_price - $discount,
        );

        return ApiService::_success($data);
    }
}
