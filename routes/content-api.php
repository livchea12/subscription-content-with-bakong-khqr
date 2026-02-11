<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Enums\SystemPermisson;


Route::group(['middleware' => 'auth:api', 'prefix' => 'contents'], function () {
    Route::middleware(['permission:' . SystemPermisson::VIEW_CONTENT->value])->group(function () {
        Route::get('/', [ContentController::class, 'index']);
        Route::get('/{content}', [ContentController::class, 'show']);
    });

    Route::middleware(['permission:' . SystemPermisson::CREATE_CONTENT->value])->group(function () {
        Route::post('/', [ContentController::class, 'store']);
    });

    Route::middleware(['permission:' . SystemPermisson::UPDATE_CONTENT->value])->group(function () {
        Route::put('/{content}', [ContentController::class, 'update']);
    });

    Route::middleware(['permission:' . SystemPermisson::DELETE_CONTENT->value])->group(function () {
        Route::delete('/{content}', [ContentController::class, 'destroy']);
    });
});
