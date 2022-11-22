<?php

namespace Modules\Cart\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shipping
 * @package Modules\Cart\Services\Shipping\Facades
 * @method static bool has($id)
 * @method static Collection all()
 * @method static array get()
 * @method static Cart put(array $value, Model $obj = null)
 */

class Shipping extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shipping';
    }
}
