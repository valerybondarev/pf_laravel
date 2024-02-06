<?php
Route::group(['prefix' => 'bot', 'namespace' => 'Bot'], function () {
    Route::post('index', 'BotController@index')->name('bot.webhook');
});
Route::group(['prefix' => 'test', 'namespace' => 'Bot'], function () {
    Route::get('', 'TestController@index');
});
