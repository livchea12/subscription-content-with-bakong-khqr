<?php

use App\Http\Controllers\AuthController;

Route::group(['middleware' => 'api', 'prefix' => 'v1/auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
});

