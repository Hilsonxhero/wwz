<?php



use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\v1\App\PaymentController;
use Modules\Payment\Http\Controllers\v1\Panel\GatewayController;
use Modules\Payment\Http\Controllers\v1\Panel\PaymentController as PanelPaymentController;
use Modules\Payment\Http\Controllers\v1\Panel\PaymentMethodController;

Route::prefix('v1/application')->group(function () {
    Route::get('payment', [PaymentController::class, 'init'])->middleware(['auth:api']);
    Route::post('payment', [PaymentController::class, 'store'])->middleware(['auth:api']);
});



Route::prefix('v1/panel')->group(function () {
    Route::apiResource("payments", PanelPaymentController::class);
    Route::apiResource("gateways", GatewayController::class);
    Route::apiResource("payment/methods", PaymentMethodController::class);
});
