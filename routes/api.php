<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function() {
    Route::get('/users', 'index');
    Route::post('/users', 'store');
    Route::get('/users/{id}', 'show');
    Route::put('/users/{id}', 'update');
    Route::delete('/users/{id}', 'destroy');
});

Route::controller(CategoryController::class)->group(function() {
    Route::get('/categories', 'index');
    Route::post('/categories', 'store');
    Route::get('/categories/{id}', 'show');
    Route::put('/categories/{id}', 'update');
    Route::delete('/categories/{id}', 'destroy');
});

Route::controller(SupplierController::class)->group(function() {
    Route::get('/suppliers', 'index');
    Route::post('/suppliers', 'store');
    Route::get('/suppliers/{id}', 'show');
    Route::put('/suppliers/{id}', 'update');
    Route::delete('/suppliers/{id}', 'destroy');

    Route::post('/suppliers/detail', 'storeDetail');
    Route::get('/suppliers/detail/{id}', 'showDetail');
});

Route::controller(ProductController::class)->group(function() {
    Route::get('/products', 'index');
    Route::post('/products', 'store');
    Route::get('/products/{id}', 'show');
    Route::put('/products/{id}', 'update');
    Route::delete('/products/{id}', 'destroy');

    Route::post('/products/{id}/restock', 'restockProduct');
});

Route::controller(CustomerController::class)->group(function() {
    Route::get('/customers', 'index');
    Route::post('/customers', 'store');
    Route::get('/customers/{id}', 'show');
    Route::put('/customers/{id}', 'update');
    Route::delete('/customers/{id}', 'destroy');
});
