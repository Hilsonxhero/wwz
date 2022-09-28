<?php

namespace Modules\Cart\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Cart\Entities\Cart;

class CartRepository implements CartRepositoryInterface
{

    public function all()
    {
        return Cart::orderBy('created_at', 'desc')->with(['items'])
            ->get();
    }

    public function create($data)
    {
        $shipment =  Cart::query()->create($data);
        return $shipment;
    }

    public function firstOrCreate($check, $data)
    {
        $cart = Cart::query()->firstOrCreate(
            $check,
            $data
        );
        return $cart;
    }

    public function update($id, $data)
    {
        $shipment = $this->find($id);
        $shipment->update($data);
        return $shipment;
    }
    public function show($id)
    {
        $shipment = $this->find($id);
        return $shipment;
    }

    public function find($id)
    {
        try {
            $shipment = Cart::query()->where('id', $id)->firstOrFail();
            return $shipment;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $shipment = $this->find($id);
        $shipment->delete();
    }
}
