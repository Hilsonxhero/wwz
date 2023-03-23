<?php

use Modules\Sms\Facades\Sms;
use Melipayamak\MelipayamakApi;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Route;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Notification;
use Modules\Order\Notifications\v1\App\OrderConfirmationNotif;
use Modules\User\Notifications\v1\App\VerifyPhoneNotification;


Route::get('/', function () {
    return view('welcome');
});
