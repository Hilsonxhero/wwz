<?php

namespace Modules\Cart\Contracts;

use Modules\Cart\Services\CartItem;

interface Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem);
}
