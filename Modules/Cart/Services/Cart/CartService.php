<?php

namespace Modules\Cart\Services\Cart;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CartService
{
    private $cart;
    public function __construct()
    {

        $this->cart = session()->get('cart') ?? collect([]);
    }

    public function get()
    {
        return "wwww";
    }

    public function put(array $value, $obj = null)
    {
        $privateKey = Str::random(16);

        if (!is_null($obj) && $obj instanceof Model) {
            $value = array_merge($value, [
                'id' => random_int(9999, 100000000),
                'private_key' => $privateKey,
                'cartable_id' => $obj->id,
                'cartable_type' => get_class($obj),
            ]);
        } else {
            $value = array_merge($value, [
                'id' => random_int(9999, 100000000),
            ]);
        }

        $this->cart->put($value['id'], $value);

        session()->put('cart', $this->cart);

        return $this;
    }

    public function has($key)
    {
        if ($key instanceof Model) {
            return !is_null($this->cart->where('cartable_id', $key)->where('cartable_type', get_class($key))->first());
        }

        return !is_null($this->cart->where('id', $key)->first());
    }
}
