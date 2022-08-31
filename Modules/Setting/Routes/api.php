<?php

use Illuminate\Http\Request;
use Modules\Setting\Http\Controllers\v1\Panel\BannerController;
use Modules\Setting\Http\Controllers\v1\Panel\SettingController;

Route::prefix('v1/panel')->group(function () {
    Route::prefix('setting')->group(function () {
        Route::apiResource("/banners", BannerController::class);
        Route::get("/variables", [SettingController::class, 'index']);
        Route::post("/variables", [SettingController::class, 'update']);
    });
});
