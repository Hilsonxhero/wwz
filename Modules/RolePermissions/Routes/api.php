<?php

use Illuminate\Http\Request;
use Modules\RolePermissions\Http\Controllers\v1\Panel\PermissionController;
use Modules\RolePermissions\Http\Controllers\v1\Panel\RoleController;

Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    Route::apiResource("/permissions", PermissionController::class);
    Route::apiResource("/roles", RoleController::class);
});
