<?php

use Modules\Sms\Facades\Sms;
use Melipayamak\MelipayamakApi;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\Comment\Entities\Comment;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Modules\Comment\Entities\CommentScore;
use Illuminate\Support\Facades\Notification;
use Modules\Comment\Enums\CommentStatus;
use Modules\Order\Notifications\v1\App\OrderConfirmationNotif;
use Modules\User\Notifications\v1\App\VerifyPhoneNotification;


Route::get('/', function () {
    return view('welcome');
});
