<?php

Route::get('profile', 'Users\ProfileController@edit')->name('profile.edit');
Route::put('profile', 'Users\ProfileController@update')->name('profile.update');