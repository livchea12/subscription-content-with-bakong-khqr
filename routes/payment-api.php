<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Enums\SystemPermisson;


Route::group(['middleware' => 'auth:api', 'prefix' => 'payment'], function () {
    Route::middleware(['permission:' . SystemPermisson::VIEW_CONTENT->value])->group(function () {
        Route::get('/generateKHQR', [PaymentController::class, 'generateKHQR']);
        Route::get('/checkPaymentStatus/{payment}', [PaymentController::class, 'checkPaymentStatus']);
    });
});
