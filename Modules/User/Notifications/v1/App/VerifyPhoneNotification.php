<?php

namespace Modules\User\Notifications\v1\App;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Sms\Notifications\v1\App\Channels\SmsChannel;


class VerifyPhoneNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $phone;
    public $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */


    public function __construct($phone, $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }


    public function toSms()
    {
        return [
            'text' => "کد تأیید {$this->code} می‌باشد. \n " . env('APP_NAME') . "",
            'phone' => $this->phone
        ];
    }
}
