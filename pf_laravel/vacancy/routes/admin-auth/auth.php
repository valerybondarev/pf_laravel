<?php

Route::group(['middleware' => 'admin.guest'], function () {
    Route::get('sign-in', 'AuthController@showLoginForm')->name('sign-in-form');
    Route::post('sign-in', 'AuthController@login')->name('sign-in');
});

Route::group(['middleware' => 'admin.auth'], function () {
    Route::post('sign-out', 'AuthController@logout')->name('sign-out');
});

