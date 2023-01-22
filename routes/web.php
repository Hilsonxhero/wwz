<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Entities\Product;


Route::get('/', function () {
    return view('welcome');
});
