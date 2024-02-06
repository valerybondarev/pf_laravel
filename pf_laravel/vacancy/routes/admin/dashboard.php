<?php

Route::group(['namespace' => 'Dashboard'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
});

