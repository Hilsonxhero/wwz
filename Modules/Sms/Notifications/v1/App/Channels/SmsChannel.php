<?php

namespace Modules\Sms\Notifications\v1\App\Channels;

use Modules\Sms\Facades\Sms;
use Illuminate\Notifications\Notification;

class SmsChannel
{

    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toSms();
        Sms::send($data['phone'], $data['text']);
    }
}
