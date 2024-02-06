<?php
Route::group(['middleware' => ['admin.auth']], function () {
    Route::group(['prefix' => 'clients'], function () {
        Route::get('downloadClientsBase', 'Client\ClientController@downloadClientsBase')->name('clients.downloadClientsBase');
        Route::post('{client}/clear-tests', 'Client\ClientController@clearTests')->name('clients.clearTests');
    });
    Route::group(['prefix' => 'client-messages'], function () {
        Route::post('{client}/send', 'Client\ClientController@send')->name('clientMessage.send');
        Route::post('{client}/test', 'Client\ClientController@test')->name('clientMessage.test');
    });
});
