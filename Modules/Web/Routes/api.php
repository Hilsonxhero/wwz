<?php

use Illuminate\Http\Request;
use Modules\Web\Http\Controllers\App\LandingController;
use Modules\Web\Http\Controllers\v1\Panel\InitController;

Route::prefix('v1/application')->group(function () {
    Route::get('init', InitController::class);
    Route::get('landing', [LandingController::class, 'index']);
});
