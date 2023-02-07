<?php

namespace Modules\Voucher\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\Voucher\Repository\VoucherRepositoryInterface;
use Modules\Voucher\Http\Requests\App\VoucherRequest;

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
    public function check(VoucherRequest $request)
    {
        $user = auth()->user();

        $user->available_cart->update([
            'config' => null
        ]);

        $cart = Cart::content();

        $payable_price = $cart->summary_price;

        $voucher = $this->voucherRepo->check($request->code);

        if (!$voucher) {
            ApiService::_throw("کد تخفیف معتبر نمی باشد", 400);
        }

        if ($voucher->is_percent) {
            $discount = $payable_price * ($voucher->value / 100);
        } else {
            $discount = $voucher->value;
        }

        $exists_voucher = $user->available_cart->config;

        if (!is_null($exists_voucher)) {
        }


        $user->available_cart->update([
            'config' => json_decode(json_encode(array(
                "voucher_id" => $voucher->id,
                "voucher_code" => $voucher->code,
                "voucher_discount" => $discount,
            )))
        ]);

        $data = array(
            "voucher_code" => $voucher->code,
            "voucher_discount" => $discount,
            "items_discount" => $cart->items_discount,
            "rrp_price" => $cart->rrp_price,
            "shipping_cost" => $cart->shipment_cost,
            "payable_price" => $payable_price - $discount + $cart->shipment_cost,
        );

        return ApiService::_success($data);
    }
}
