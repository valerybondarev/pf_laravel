<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\API;
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

require __DIR__.'/web/auth.php';

Route::get('/', function () {
    return response('ok1');
});
Route::get('/files', function () {
    return view('welcome');
});
Route::post('/files/set', [\App\Http\Controllers\FileController::class, 'setFile']);
Route::get('files/download/{file}', [\App\Http\Controllers\FileController::class, 'download'])->name('files.download');

Route::get('/admin/sessions', [Admin\SessionController::class, 'show'])->name('sessions');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    Route::post('sessions/{session}', [Admin\SessionController::class, 'change'])->name('sessions.change');
    Route::resource('sessions', Admin\SessionController::class, ['only' => ['index', 'show']]);

    Route::resource('roles', Admin\RoleController::class, ['except' => ['show']]);
    Route::resource('users', Admin\UserController::class);
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::post('impersonate/{user}',
            [Admin\UserController::class, 'impersonate'])->name('impersonate'); // need realize
    });
});

//Route::group(['middleware' => 'auth:api'], function () {
    Route::post('upload', [API\SessionController::class, 'upload']);
    Route::get('status/{session:name}', [API\SessionController::class, 'status']);
//});
