<?php

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Controllers\v1\Panel\DashboardController;

Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    Route::apiResource("/dashboard", DashboardController::class);
});
