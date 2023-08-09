<?php

use App\Http\Controllers\Api\ApiArticleController;
use App\Http\Controllers\Api\ApiBannerController;
use App\Http\Controllers\Api\ApiDiagnosticController;
use App\Http\Controllers\Api\ApiGrievanceControlller;
use App\Http\Controllers\Api\ApiOutletController;
use App\Http\Controllers\Api\ApiPartnerController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiProductCategoryController;
use App\Models\Grievance;
use Illuminate\Support\Facades\Request;
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

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
Route::middleware('auth:api')->prefix('be')->group(function () {
    // Route::prefix('be')->group(function () {

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
        Route::get($outlet, 'show')->name('outlet.show');
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
    Route::controller(ApiArticleController::class)->prefix('/article')->group(function () {
        $article = '{article}';
        Route::get('', 'index')->name('article.list');
        Route::post('', 'store')->name('article.create');
        Route::get($article, 'show')->name('article.show');
        Route::patch($article, 'update')->name('article.update');
        Route::delete($article, 'destroy')->name('article.delete');
    });
    Route::controller(ApiPartnerController::class)->prefix('/partner')->group(function () {
        $partner = '{partner}';
        Route::get('', 'index')->name('partner.list');
        Route::post('', 'store')->name('partner.create');
        Route::get($partner, 'show')->name('partner.show');
        Route::patch($partner, 'update')->name('partner.update');
        Route::delete($partner, 'destroy')->name('partner.delete');
    });
    Route::controller(ApiGrievanceControlller::class)->prefix('/grievance')->group(function () {
        $grievance = '{grievance}';
        Route::get('', 'index')->name('grievance.list');
        Route::post('', 'store')->name('grievance.create');
        Route::get($grievance, 'show')->name('grievance.show');
        Route::patch($grievance, 'update')->name('grievance.update');
        Route::delete($grievance, 'destroy')->name('grievance.delete');
    });
    Route::controller(ApiDiagnosticController::class)->prefix('/diagnostic')->group(function () {
        $diagnostic = '{diagnostic}';
        Route::get('', 'index')->name('diagnostic.list');
        Route::post('', 'store')->name('diagnostic.create');
        Route::get($diagnostic, 'show')->name('diagnostic.show');
        Route::patch($diagnostic, 'update')->name('diagnostic.update');
        Route::delete($diagnostic, 'destroy')->name('diagnostic.delete');
    });
    Route::controller(ApiBannerController::class)->prefix('/banner')->group(function () {
        $banner = '{banner}';
        Route::get('', 'index')->name('banner.list');
        Route::get($banner, 'show')->name('banner.show');
        Route::patch($banner, 'update')->name('banner.update');
    });
});