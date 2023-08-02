<?php

use App\Http\Controllers\Api\ApiOutletController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiProductCategoryController;
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
// Route::middleware('auth:api')->prefix('be')->group(function () {
Route::prefix('be')->group(function () {

    Route::controller(ApiUserController::class)->prefix('/user')->group(function () {
        $user = '{user}';
        Route::get('', 'index')->name('user.list');
        Route::get('detail', 'detailUser')->name('user.detail');
        Route::post('', 'store')->name('user.create');
        Route::get($user, 'show')->name('user.show');
        Route::patch($user, 'update')->name('user.update');
        Route::delete($user, 'destroy')->name('user.delete');
    });
    Route::controller(ApiOutletController::class)->prefix('/outlet')->group(function () {
        $outlet = '{outlet}';
        Route::get('', 'index')->name('outlet.list');
        Route::post('', 'store')->name('outlet.create');
        Route::get($outlet, 'shown')->name('outlet.show');
        Route::patch($outlet, 'update')->name('outlet.update');
        Route::delete($outlet, 'destroy')->name('outlet.delete');
    });
    Route::controller(ApiProductController::class)->prefix('/product')->group(function () {
        $product = '{product}';
        Route::get('', 'index')->name('product.list');
        Route::post('', 'store')->name('product.create');
        Route::get($product, 'show')->name('product.show');
        Route::patch($product, 'update')->name('product.update');
        Route::delete($product, 'destroy')->name('product.delete');
    });
    Route::controller(ApiProductCategoryController::class)->prefix('/product-category')->group(function () {
        $product_category = '{product_category}';
        Route::get('', 'index')->name('product-category.list');
        Route::post('', 'store')->name('product-category.create');
        Route::get($product_category, 'show')->name('product-category.show');
        Route::patch($product_category, 'update')->name('product-category.update');
        Route::delete($product_category, 'destroy')->name('product-category.delete');
    });
});