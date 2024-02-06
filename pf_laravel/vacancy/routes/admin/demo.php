<?php

Route::group(['namespace' => 'Demo'], function () {
    Route::get('demo', 'DemoController@index')->name('demo');
});

