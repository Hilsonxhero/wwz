<?php

namespace Modules\Sms\Drivers\Melipayamak;

use Melipayamak\MelipayamakApi;
use Modules\Sms\Abstracts\Driver;

class Melipayamak extends Driver
{
    public function send($phone, $content)
    {
        try {
            $username = config('services.sms.melipayamak.username');
            $password = config('services.sms.melipayamak.password');
            $api = new MelipayamakApi($username, $password);
            $sms = $api->sms();
            $to = $phone;
            $from = config('services.sms.melipayamak.from');
            $text = $content;
            $response = $sms->send($to, $from, $text);
            $json = json_decode($response);
            // echo $json->Value;
        } catch (\Exception $e) {
            // echo $e->getMessage();
        }
    }
}
