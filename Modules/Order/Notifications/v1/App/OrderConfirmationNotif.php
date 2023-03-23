<?php

namespace Modules\Order\Notifications\v1\App;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Sms\Notifications\v1\App\Channels\SmsChannel;

class OrderConfirmationNotif extends Notification implements ShouldQueue
{
    use Queueable;
    public $phone;
    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($phone, $order)
    {
        $this->phone = $phone;
        $this->order = $order;
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
            'text' => "سفارش شما با شماره {$this->order->reference_code} با موفقیت ثبت شد \n " . env('APP_NAME') . "",
            'phone' => $this->phone
        ];
    }
}
