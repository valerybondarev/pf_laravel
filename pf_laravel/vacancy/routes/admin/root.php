<?php

Route::group(['middleware' => 'permission:system'], function () {
    Route::resources([
        'users' => 'Users\UserController',
    ]);
});
