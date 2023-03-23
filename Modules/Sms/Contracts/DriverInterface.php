<?php

namespace Modules\Sms\Contracts;

interface DriverInterface
{
    public function send($phone, $content);
}
