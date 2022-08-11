<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Modules\Category\Entities\Category;

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

function flat($array, &$return)
{
    if (is_array($array)) {
        array_walk_recursive($array, function ($a) use (&$return) {
            flat($a, $return);
        });
    } else if (is_string($array) && stripos($array, '[') !== false) {
        $array = explode(',', trim($array, "[]"));
        flat($array, $return);
    } else {
        $return[] = $array;
    }
}

Route::get('/', function () {
    return view('welcome');
});
