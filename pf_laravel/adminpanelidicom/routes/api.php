<?php

use App\Http\Controllers\API;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('upload', [API\SessionController::class, 'upload']);
    Route::get('status/{session:name}', [API\SessionController::class, 'status']);
});

Route::post('token/create', [API\TokenController::class, 'create']);
