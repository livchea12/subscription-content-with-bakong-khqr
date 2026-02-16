<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionPlanController;

Route::group(['prefix' => 'v1'], function () {

    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index']);
    require __DIR__ . '/auth-api.php';
    require __DIR__ . '/content-api.php';
    require __DIR__ . '/userSubscription-api.php';
    require __DIR__ . '/payment-api.php';
});
