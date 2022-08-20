<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Modules\Category\Entities\Category;
use Morilog\Jalali\Jalalian;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});
