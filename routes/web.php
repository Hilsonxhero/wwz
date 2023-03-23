<?php

use Modules\Sms\Facades\Sms;
use Melipayamak\MelipayamakApi;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Route;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Illuminate\Support\Facades\Notification;
use Modules\Order\Notifications\v1\App\OrderConfirmationNotif;
use Modules\User\Notifications\v1\App\VerifyPhoneNotification;


Route::get('/', function () {
    $category = Category::find(51);
    return $category->products_count;
    return view('welcome');
});
