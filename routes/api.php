<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'prefix' => 'v1/'], function () {

    require __DIR__ . '/auth-api.php';
    require __DIR__ . '/content-api.php';
});
