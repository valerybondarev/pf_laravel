<?php

Route::group(['middleware' => ['admin.auth']], function () {
    Route::group(['prefix' => 'mailings', 'namespace' => 'Mailing'], function () {
        Route::post('send-test', 'MailingController@sendTest');
        Route::put('send-test', 'MailingController@sendTest');
        Route::get('get-button', 'MailingController@getButton');
    });
});