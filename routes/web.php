<?php


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('foo', function () {
    echo "foo";
    sleep(20);
});

Route::get('ping', function () {
    echo "ping";
});
