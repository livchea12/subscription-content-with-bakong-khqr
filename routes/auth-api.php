<?php

use App\Http\Controllers\AuthController;

Route::group(['middleware' => 'api', 'prefix' => 'v1/auth'], function () {
    // Public routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Protected routes
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::put('reset-password', [AuthController::class, 'resetPassword']);
        Route::get('profile', [AuthController::class, 'getProfile']);
    });
});

