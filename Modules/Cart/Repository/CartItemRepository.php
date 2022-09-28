<?php

namespace Modules\Cart\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Cart\Entities\CartItem;

class CartItemRepository implements CartItemRepositoryInterface
{

    public function all()
    {
        return CartItem::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return CartItem::orderBy('created_at', 'desc')
            ->paginate();
    }


    public function create($data)
    {
        $item =  CartItem::query()->create($data);
        return $item;
    }
    public function firstOrCreate($check, $data)
    {
        $item =  CartItem::query()->firstOrCreate($check, $data);
        return $item;
    }
    public function update($id, $data)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }
    public function show($id)
    {
        $item = $this->find($id);
        return $item;
    }

    public function find($id)
    {
        try {
            $item = CartItem::query()->where('id', $id)->firstOrFail();
            return $item;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function findByVariant($cart, $variant)
    {
        return $cart->items()->where("variant_id", $variant)->first();
    }

    public function findByUUID($cart, $id)
    {
        return $cart->items()->where("uuid", $id)->first();
    }


    public function delete($id)
    {
        $item = $this->find($id);
        $item->delete();
    }
}
