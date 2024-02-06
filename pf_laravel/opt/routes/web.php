<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// Создание заказа

// Редактирование заказа

// Удаление заказа


// Информация по отдельному заказу


Route::prefix('/')->controller(ProductController::class)->group(function () {

    Route::get('product', 'createForm');
    Route::post('product', 'setProduct')->name('setProduct');

    //Route::get('product/{product_id}', 'getProductInfo');
    Route::delete('product/{product_id}', 'deleteProduct')->name('deleteProduct');

    Route::get('ProductList', 'list');
    Route::get('ProductList/{order_id}', 'list');
    Route::get('product/{order_id}', 'createForm');
});



Route::prefix('api')->controller(OrderController::class)->group(function () {
    Route::get('DashboardList', 'DashboardList');


    Route::get('order/{order_id}','getOrderInfo')->name('getOrderInfo');

    Route::post('order', 'setOrder')->name('setOrder');
    Route::delete('order/{order_id}', 'deleteOrder')->name('deleteOrder');
    // Хуита
});


require __DIR__.'/auth.php';


/*
DELIMITER //

CREATE PROCEDURE insertToUsers1()
BEGIN
DECLARE i INT DEFAULT 1;
    WHILE i<1000
DO
    INSERT INTO order(phone, email, address) VALUES(CONCAT('phone',i), CONCAT('email', i), CONCAT('address', i);
        SET i = i + 1;
    END WHILE;
    END;




DELIMITER //

CREATE PROCEDURE insertToUsers1()
BEGIN
DECLARE i INT DEFAULT 1;
    WHILE i<1000
DO
    INSERT INTO `order`(phone, email, address) VALUES(i*21/6, CONCAT('email', i,'@',i+1), CONCAT('address', i));
        SET i = i + 1;
    END WHILE;
    END;
 */
