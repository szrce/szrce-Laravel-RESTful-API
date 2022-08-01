<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrdersController;
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

Route::get('/products/view', [ProductsController::class, 'index']);
Route::post('/products/add', [ProductsController::class, 'store']);

Route::post('/products/customeradd', [CustomersController::class, 'store']);
Route::post('/products/orderAdd', [OrdersController::class, 'store']);
Route::post('/products/orderDell', [OrdersController::class, 'destroy']);
