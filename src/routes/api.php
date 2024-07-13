<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\SellerProductController;
use App\Http\Controllers\Api\ShopController;
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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/add/shop', [SellerController::class, 'addShop'])->middleware('isSeller');
    Route::get('/get/shop', [SellerController::class, 'getShop'])->middleware('isSeller');
    Route::put('/update/shop/{id}', [SellerController::class, 'updateShop'])->middleware('isSeller');
    Route::delete('/delete/shop/{id}', [SellerController::class, 'deleteShop'])->middleware('isSeller');

    Route::post('/add/product', [SellerProductController::class, 'addProducts'])->middleware('isSeller');
    Route::get('/get/product', [SellerProductController::class, 'getProducts'])->middleware('isSeller');
    Route::put('/update/product/{id}', [SellerProductController::class, 'updateProducts'])->middleware('isSeller');
    Route::delete('/delete/product/{id}', [SellerProductController::class, 'deleteProducts'])->middleware('isSeller');


    ///
    Route::post('/admin/add/product', [AdminController::class, 'addProducts'])->middleware('isAdmin');
    Route::get('/admin/get/product', [AdminController::class, 'getProducts'])->middleware('isAdmin');
    Route::put('/admin/update/product/{id}', [AdminController::class, 'updateProducts'])->middleware('isAdmin');
    Route::delete('/admin/delete/product/{id}', [AdminController::class, 'deleteProducts'])->middleware('isAdmin');

    Route::post('/admin/add/shop', [ShopController::class, 'addShop'])->middleware('isAdmin');
    Route::get('/admin/get/shop', [ShopController::class, 'getShop'])->middleware('isAdmin');
    Route::put('/admin/update/shop/{id}', [ShopController::class, 'updateShop'])->middleware('isAdmin');
    Route::delete('/admin/delete/shop/{id}', [ShopController::class, 'deleteShop'])->middleware('isAdmin');
});


Route::middleware(['guest:sanctum'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});



