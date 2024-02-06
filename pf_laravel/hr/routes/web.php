<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
    Route::get('lang/{locale}', [App\Http\Controllers\LocalizationController::class, 'index']);

    Route::get('/', 'App\Http\Controllers\CandidateController@welcome')->name('candidate.welcome');
    Route::get('/candidate', 'App\Http\Controllers\CandidateController@index')->name('candidate');
    Route::get('/candidate/{slug}', 'App\Http\Controllers\CandidateController@view')->name('candidate.view');

//регистрация
    Route::get('register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register');
    Route::post('register', 'App\Http\Controllers\Auth\RegisterController@create')->name('create');
    Route::get('login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');

// аутентификация
    Route::post('login', 'App\Http\Controllers\Auth\LoginController@authenticate')->name('auth');
// выход
    Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
// форма ввода адреса почты
    Route::get('forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@form')->name('forgot-form');
// письмо на почту
    Route::post('forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@mail')->name('forgot-mail');
// форма восстановления пароля
    Route::get(
        'reset-password/token/{token}/email/{email}',
        'App\Http\Controllers\Auth\ResetPasswordController@form'
    )->name('reset-form');
// восстановление пароля
    Route::post('reset-password', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('reset-password');

    Route::prefix('admin')->group(function () {
        Route::post('user/create-experience/{id}', 'App\Http\Controllers\User\IndexController@createExperience');
        Route::post('user/delete-experience/{id}', 'App\Http\Controllers\User\IndexController@deleteExperience');
        Route::post('user/save-pdf', 'App\Http\Controllers\User\IndexController@savePdf')->name('user.createPdf');
        Route::resource('user', App\Http\Controllers\User\IndexController::class);
        //справочники
        Route::get('guide', 'App\Http\Controllers\GuideController@index')->name('guide');
        //добавление нового
        Route::post('guide/create', 'App\Http\Controllers\GuideController@create')->name('guide.create');
        //обновление
        Route::post('guide/update', 'App\Http\Controllers\GuideController@update')->name('guide.update');
        //добавление нового
        Route::post('guide/delete', 'App\Http\Controllers\GuideController@destroy')->name('guide.destroy');
        //выбор по умолчанию
        Route::post('guide/default', 'App\Http\Controllers\GuideController@default')->name('guide.default');

        // список сотрудников
        Route::get('/', 'App\Http\Controllers\User\IndexController@welcome')->name('welcome');
        // форма ввода почты для регистрации
        Route::get('invite', 'App\Http\Controllers\Auth\InviteController@form')->name('invite');
        Route::post('invite', 'App\Http\Controllers\Auth\InviteController@mail')->name('invite-mail');

        // вакансии
        Route::resource('vacancie', App\Http\Controllers\VacancieController::class)->name('index', 'vacancie');
    });

