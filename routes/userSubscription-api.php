<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSubscriptionController;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Enums\SystemRole;


Route::group(['middleware' => ['auth:api', RoleMiddleware::class . ':' . SystemRole::USER->value], 'prefix' => 'user-subscription'], function () {
    Route::post('/subscribe/{subscriptionPlan}', [UserSubscriptionController::class, 'subscribe']);
    Route::get('/subscribe/me', [UserSubscriptionController::class, 'userSubscriptionState']);

});
