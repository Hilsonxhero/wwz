<?php

namespace Modules\Sms\Abstracts;

use Modules\Sms\Contracts\DriverInterface;

abstract class Driver implements DriverInterface
{
    abstract public function send($phone, $content);
}
