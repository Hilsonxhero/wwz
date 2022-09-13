<?php

namespace Modules\Cart\Services\Cart\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * @package Modules\Cart\Services\Cart\Facades
 * @method static bool has($id)
 * @method static Collection all()
 * @method static array get()
 * @method static Cart put(array $value, Model $obj = null)
 */

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
