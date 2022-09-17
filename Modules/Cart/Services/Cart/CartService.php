<?php

namespace Modules\Cart\Services\Cart;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Model;

class CartService
{
    private $storage;
    private $cart;
    private $cart_token;
    public function __construct()
    {
        $this->storage  = Redis::connection();
        $this->cart_token  = request()->cookie('cart_token');
        $this->cart = $this->init() ?? collect([]);
    }

    public function init()
    {

        $cart_token = $this->cart_token;
        if (!$cart_token) return null;
        $cart = json_decode($this->storage->get($cart_token));
        return collect($cart);
    }

    public function get()
    {
        return $this->cart;
    }

    public function all()
    {
        return $this->cart;
    }


    public function put($value, $obj = null)
    {
        $cart_token = $this->cart_token;

        if (!$cart_token) {
            $token = Str::random(12);
            $cart_token =  Cookie::queue(
                'cart_token',
                $token,
                999999,
                null,
                null,
                false,
                true,
                false,
                'Strict'
            );
        } else {
            $token = $cart_token;
        }

        // $cart = array_merge($this->cart, [
        //     'id' => random_int(9999, 100000000),
        //     'cart_token' => $cart_token,
        // ]);

        // $this->cart = $cart;

        if (!is_null($obj)) {
            $value = array_merge($value, [
                'id' => random_int(9999, 100000000),
                // 'cart_id' => $cart['id'],
                // 'payable_price' => 685180000,
                // 'rrp_price' => 706300000,
                // 'items_discount' => 21120000,
                // 'total_discount' => 21120000,
                // 'items_count' => 3,
                // 'private_key' => $privateKey,
            ]);
            $this->cart->push($value);
        } else {
        }
        $this->storage->set($token, json_encode($this->cart));

        return $this;
    }


    public function update($key)
    {
        $item = $this->cart->where('variant.id', $key)->first();
        $item->quantity = (int) $item->quantity + 1;
        $this->put($item);
        return $this;
    }

    public function has($key)
    {
        return !is_null($this->cart->where('variant.id', $key)->first());
    }
}
