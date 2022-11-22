<?php

namespace Modules\Cart\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Cart\Entities\Cart;
use Modules\Cart\Entities\Shipping;

class ShippingRepository implements ShippingRepositoryInterface
{

    public function all()
    {
        return Cart::orderBy('created_at', 'desc')->with(['items'])
            ->get();
    }

    public function create($data)
    {
        $shipping =  Shipping::query()->create($data);
        return $shipping;
    }

    public function firstOrCreate($check, $data)
    {
        $cart = Shipping::query()->firstOrCreate(
            $check,
            $data
        );
        return $cart;
    }

    public function update($id, $data)
    {
        $shipping = $this->find($id);
        $shipping->update($data);
        return $shipping;
    }
    public function show($id)
    {
        $shipping = $this->find($id);
        return $shipping;
    }

    public function find($id)
    {
        try {
            $shipping = Shipping::query()->where('id', $id)->firstOrFail();
            return $shipping;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $shipping = $this->find($id);
        $shipping->delete();
    }
}
