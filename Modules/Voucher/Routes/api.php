<?php

use Illuminate\Http\Request;
use Modules\Voucher\Http\Controllers\v1\Panel\VoucherableController;
use Modules\Voucher\Http\Controllers\v1\Panel\VoucherController;

Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    Route::apiResource("/voucherables", VoucherableController::class);
    Route::apiResource("/vouchers", VoucherController::class);
    Route::get("/voucher/{id}/voucherables", [VoucherController::class, 'voucherables']);
});


Route::prefix('v1/application')->group(function () {
    Route::post("/voucher/check", [\Modules\Voucher\Http\Controllers\v1\App\VoucherController::class, 'check'])->middleware(['auth:api']);
});
