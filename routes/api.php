<?php

use App\Http\Controllers\Api\ApiArticleController;
use App\Http\Controllers\Api\ApiBannerController;
use App\Http\Controllers\Api\ApiDiagnosticController;
use App\Http\Controllers\Api\ApiGrievanceControlller;
use App\Http\Controllers\Api\ApiOutletController;
use App\Http\Controllers\Api\ApiPartnerController;
use App\Http\Controllers\Api\ApiPartnerEqualController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiProductCategoryController;
use App\Models\Grievance;
use Illuminate\Support\Facades\Log;
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
    Route::controller(ApiUserController::class)->prefix('/user')->group(function () {
        $user = '{user}';
        Route::get('', 'index')->name('api.user.list');
        Route::get('detail', 'detailUser')->name('api.user.detail');
        Route::post('', 'store')->name('api.user.create');
        Route::get($user, 'show')->name('api.user.show');
        Route::patch($user, 'update')->name('api.user.update');
        Route::delete($user, 'destroy')->name('api.user.delete');
    });
    Route::controller(ApiOutletController::class)->prefix('/outlet')->group(function () {
        $outlet = '{outlet}';
        Route::get('', 'index')->name('api.outlet.list');
        Route::get('name-id', 'nameId')->name('api.outlet.name.id');
        Route::post('', 'store')->name('api.outlet.create');
        Route::get($outlet, 'show')->name('api.outlet.show');
        Route::patch($outlet, 'update')->name('api.outlet.update');
        Route::delete($outlet, 'destroy')->name('api.outlet.delete');
    });
    Route::controller(ApiProductController::class)->prefix('/product')->group(function () {
        $product = '{product}';
        Route::get('', 'index')->name('api.product.list');
        Route::post('', 'store')->name('api.product.create');
        Route::get($product, 'show')->name('api.product.show');
        Route::patch($product, 'update')->name('api.product.update');
        Route::delete($product, 'destroy')->name('api.product.delete');
    });
    Route::controller(ApiProductCategoryController::class)->prefix('/product-category')->group(function () {
        $product_category = '{product_category}';
        Route::get('', 'index')->name('api.product-category.list');
        Route::post('', 'store')->name('api.product-category.create');
        Route::get($product_category, 'show')->name('api.product-category.show');
        Route::patch($product_category, 'update')->name('api.product-category.update');
        Route::delete($product_category, 'destroy')->name('api.product-category.delete');
    });
    Route::controller(ApiArticleController::class)->prefix('/article')->group(function () {
        $article = '{article}';
        Route::get('', 'index')->name('api.article.list');
        Route::post('', 'store')->name('api.article.create');
        Route::get($article, 'show')->name('api.article.show');
        Route::patch($article, 'update')->name('api.article.update');
        Route::delete($article, 'destroy')->name('api.article.delete');
    });
    Route::controller(ApiPartnerController::class)->prefix('/partner')->group(function () {
        $partner = '{partner}';
        Route::get('', 'index')->name('api.partner.list');
        Route::post('', 'store')->name('api.partner.create');
        Route::get($partner, 'show')->name('api.partner.show');
        Route::patch($partner, 'update')->name('api.partner.update');
        Route::delete($partner, 'destroy')->name('api.partner.delete');
    });
    Route::controller(ApiPartnerEqualController::class)->prefix('/partner_equal')->group(function () {
        $partner = '{partner_equal}';
        Route::get('', 'index')->name('api.partner_equal.list');
        Route::post('', 'store')->name('api.partner_equal.create');
        Route::get($partner, 'show')->name('api.partner_equal.show');
        Route::patch($partner, 'update')->name('api.partner_equal.update');
        Route::delete($partner, 'destroy')->name('api.partner_equal.delete');
    });
    Route::controller(ApiGrievanceControlller::class)->prefix('/grievance')->group(function () {
        $grievance = '{grievance}';
        Route::get('', 'index')->name('api.grievance.list');
        Route::post('', 'store')->name('api.grievance.create');
        Route::get($grievance, 'show')->name('api.grievance.show');
        Route::patch($grievance, 'update')->name('api.grievance.update');
        Route::delete($grievance, 'destroy')->name('api.grievance.delete');
    });
    Route::controller(ApiDiagnosticController::class)->prefix('/diagnostic')->group(function () {
        $diagnostic = '{diagnostic}';
        Route::get('', 'index')->name('api.diagnostic.list');
        Route::post('', 'store')->name('api.diagnostic.create');
        Route::get($diagnostic, 'show')->name('api.diagnostic.show');
        Route::patch($diagnostic, 'update')->name('api.diagnostic.update');
        Route::delete($diagnostic, 'destroy')->name('api.diagnostic.delete');
    });
    Route::controller(ApiBannerController::class)->prefix('/banner')->group(function () {
        $banner = '{banner}';
        Route::get('', 'index')->name('api.banner.list');
        Route::get($banner, 'show')->name('api.banner.show');
        Route::patch($banner, 'update')->name('api.banner.update');
    });
});

Route::get('test-api', function () {
    Log::info("test log info");
    return "ok";
});