<?php


Route::get('/', fn() => redirect(route('admin.dashboard')))->name('index');
Route::get('/tourClubLanding/{tourClub}', 'TourClubLanding\TourClubLanding@show')->name('tourClubLanding.show');
Route::get('/', fn() => redirect(route('admin.dashboard')))->name('index');
