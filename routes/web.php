<?php


use Modules\Sms\Facades\Sms;
use Melipayamak\MelipayamakApi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
