<?php

use Illuminate\Http\Request;
use Modules\Page\Http\Controllers\v1\Panel\PageController;



Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    Route::apiResource('pages', PageController::class);
});
