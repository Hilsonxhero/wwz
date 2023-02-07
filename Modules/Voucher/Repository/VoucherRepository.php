<?php

namespace Modules\Voucher\Repository;

use Carbon\Carbon;
use App\Services\ApiService;
use Modules\Cart\Facades\Cart;
use Modules\User\Entities\User;
use Modules\Article\Entities\Article;
use Modules\Product\Entities\Product;
use Modules\Voucher\Entities\Voucher;
use Modules\Category\Entities\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VoucherRepository implements VoucherRepositoryInterface
{

    public function all()
    {
        return Voucher::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function check($code)
    {
        $user = auth()->user();

        $cart = Cart::content();

        $voucher =  Voucher::query()->where('code', $code)
            ->where('start_date', '<',  Carbon::now()->toDateString())
            ->where('end_date', '>',  Carbon::now()->toDateString())
            ->whereRaw('usage_limit_per_voucher > used')
            ->where('is_active', true)
            ->first();

        if (is_null($voucher)) {
            return false;
        }

        $voucherables = $voucher->voucherables;

        $user_voucher = $voucherables->where('voucherable_type', User::class)->all();

        $category_voucher = $voucherables->where('voucherable_type', Category::class)->all();

        $product_voucher = $voucherables->where('voucherable_type', Product::class)->all();

        $contain_user_valid = true;
        $contain_category_valid = true;
        $contain_product_valid = true;

        if (count($user_voucher) >= 1) {

            $contain_user = array();
            foreach ($user_voucher as $key => $user_item) {
                if ($user_item->voucherable_id == $user->id) {
                    array_push($contain_user, $user->id);
                }
            }
            if (!count($contain_user) >= 1) {
                $contain_user_valid = false;
            }
        }

        if (count($category_voucher) >= 1) {
            $contain_category = array();
            foreach ($cart->items as $key => $cart_item) {
                foreach ($category_voucher as $key => $item) {
                    if ($item->voucherable_id == $cart_item->product->category_id) {
                        array_push($contain_category, $cart_item);
                    }
                }
            }
            if (!count($contain_category) >= 1) {
                $contain_category_valid = false;
            } else {
                $grouped = collect($contain_category)->groupBy('product.category_id')->transform(function ($group_item) {
                    return $group_item->reduce(function ($carry, $item) {
                        return $carry + $item->price;
                    });
                })->every(function ($value, $key) use ($voucher) {
                    return $value > $voucher->minimum_spend &&  $value < $voucher->maximum_spend;
                });
                $contain_category_valid = $grouped;
            }
        }

        if (count($product_voucher) >= 1) {
            $contain_product = array();
            foreach ($cart->items as $key => $cart_item) {
                foreach ($product_voucher as $key => $item) {
                    if ($item->voucherable_id == $cart_item->product->id) {
                        array_push($contain_product, $cart_item);
                    }
                }
            }
            if (!count($contain_product) >= 1) {
                $contain_product_valid = false;
            } else {
                $grouped = collect($contain_product)->groupBy('product.category_id')->transform(function ($group_item) {
                    return $group_item->reduce(function ($carry, $item) {
                        return $carry + $item->price;
                    });
                })->every(function ($value, $key) use ($voucher) {
                    return $value > $voucher->minimum_spend &&  $value < $voucher->maximum_spend;
                });
                $contain_product_valid = $grouped;
            }
        }

        if ($contain_user_valid && $contain_category_valid && $contain_product_valid) {
            return $voucher;
        }

        return false;
    }

    public function take()
    {
        return Voucher::with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
    }


    public function create($data)
    {
        $voucher =  Voucher::query()->create($data);
        return $voucher;
    }

    public function createMany($data)
    {
        $voucher =  Voucher::query()->createMany($data);
        return $voucher;
    }

    public function update($id, $data)
    {
        $voucher = $this->find($id);
        $voucher->update($data);
        return $voucher;
    }
    public function show($id)
    {
        $voucher = $this->find($id);
        return $voucher;
    }

    public function voucherables($id)
    {
        $voucher = $this->find($id);
        return $voucher->voucherables()->paginate();
    }

    public function find($id)
    {
        try {
            $voucher = Voucher::query()->where('id', $id)->firstOrFail();
            return $voucher;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $voucher = $this->find($id);
        $voucher->delete();
    }
}
