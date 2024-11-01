<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{username}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // Units
    Route::prefix('units')->group(function () {
        Route::get('/', [UnitController::class, 'index']);
        Route::post('/', [UnitController::class, 'store']);
        Route::get('/{id}', [UnitController::class, 'show']);
        Route::put('/{id}', [UnitController::class, 'update']);
        Route::delete('/{id}', [UnitController::class, 'destroy']);
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});

// -----------------------------------------------------------------------

/* Route::controller(SupplierController::class)->group(function() {
    Route::get('/suppliers', 'index');
    Route::post('/suppliers', 'store');
    Route::get('/suppliers/{id}', 'show');
    Route::put('/suppliers/{id}', 'update');
    Route::delete('/suppliers/{id}', 'destroy');

    Route::post('/suppliers/detail', 'storeDetail');
    Route::get('/suppliers/detail/{id}', 'showDetail');
});

Route::controller(CustomerController::class)->group(function() {
    Route::get('/customers', 'index');
    Route::post('/customers', 'store');
    Route::get('/customers/{id}', 'show');
    Route::put('/customers/{id}', 'update');
    Route::delete('/customers/{id}', 'destroy');
});

Route::controller(EmployeeController::class)->group(function() {
    Route::get('/employees', 'index');
    Route::post('/employees', 'store');
    Route::get('/employees/{id}', 'show');
    Route::put('/employees/{id}', 'update');
    Route::delete('/employees/{id}', 'destroy');
}); */
