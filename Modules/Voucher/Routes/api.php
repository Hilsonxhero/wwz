<?php

use Illuminate\Http\Request;
use Modules\Voucher\Http\Controllers\v1\Panel\VoucherController;

Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/vouchers", VoucherController::class);
});
