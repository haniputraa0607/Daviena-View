<?php

use App\Http\Controllers\Api\ApiArticleController;
use App\Http\Controllers\Api\ApiBannerController;
use App\Http\Controllers\Api\ApiCustomerController;
use App\Http\Controllers\Api\ApiDiagnosticController;
use App\Http\Controllers\Api\ApiDoctorScheduleController;
use App\Http\Controllers\Api\ApiDoctorScheduleDateController;
use App\Http\Controllers\Api\ApiDoctorShift;
use App\Http\Controllers\Api\ApiGrievanceControlller;
use App\Http\Controllers\Api\ApiOutletController;
use App\Http\Controllers\Api\ApiOutletScheduleController;
use App\Http\Controllers\Api\ApiPartnerController;
use App\Http\Controllers\Api\ApiPartnerEqualController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiProductPackageController;
use App\Http\Controllers\Api\ApiProductCategoryController;
use App\Http\Controllers\Api\ApiProductTrendingController;
use App\Http\Controllers\Api\ApiOfficialPartnerController;
use App\Http\Controllers\Api\ApiContactMessageController;
use App\Http\Controllers\Api\ApiProductFinestController;
use App\Http\Controllers\Api\ApiOfficialPartnerHomeController;
use App\Http\Controllers\Api\ApiArticleRecommendationController;
use App\Http\Controllers\Api\ApiBannerClinicController;
use App\Http\Controllers\Api\ApiContactOfficialController;
use Illuminate\Support\Facades\Log;
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
        Route::get('name-id', 'nameId')->name('api.user.name.id');
        Route::get('doctor', 'getDoctorList')->name('api.user.list-doctor');
        Route::get('cashier', 'getCashierList')->name('api.user.list-cashier');
        Route::get('detail', 'detailUser')->name('api.user.detail');
        Route::post('', 'store')->name('api.user.create');
        Route::get($user, 'show')->name('api.user.show');
        Route::patch($user, 'update')->name('api.user.update');
        Route::delete($user, 'destroy')->name('api.user.delete');
    });
    Route::controller(ApiDoctorShift::class)->prefix('/doctor-shift')->group(function () {
        $doctorShift = '{doctorShift}';
        Route::get('', 'index')->name('api.doctor-shift.list');
        Route::get('name-id', 'nameId')->name('api.doctor-shift.name-id');
        Route::post('', 'store')->name('api.doctor-shift.create');
        Route::get($doctorShift, 'show')->name('api.doctor-shift.show');
        Route::patch($doctorShift, 'update')->name('api.doctor-shift.update');
        Route::delete($doctorShift, 'destroy')->name('api.doctor-shift.delete');
    });
    Route::controller(ApiDoctorScheduleController::class)->prefix('/doctor-schedule')->group(function () {
        $doctorSchedule = '{doctorSchedule}';
        Route::get('', 'index')->name('api.doctor-schedule.list');
        Route::get('name-id', 'nameId')->name('api.doctor-schedule.name-id');
        Route::post('', 'store')->name('api.doctor-schedule.create');
        Route::get($doctorSchedule, 'show')->name('api.doctor-schedule.show');
        Route::patch($doctorSchedule, 'update')->name('api.doctor-schedule.update');
        Route::delete($doctorSchedule, 'destroy')->name('api.doctor-schedule.delete');
    });
    Route::controller(ApiDoctorScheduleDateController::class)->prefix('/doctor-schedule-date')->group(function () {
        $doctorScheduleDate = '{doctorScheduleDate}';
        Route::get('', 'index')->name('api.doctor-schedule-date.list');
        Route::get('name-id', 'nameId')->name('api.doctor-schedule-date.name-id');
        Route::post('', 'store')->name('api.doctor-schedule-date.create');
        Route::get($doctorScheduleDate, 'show')->name('api.doctor-schedule-date.show');
        Route::patch($doctorScheduleDate, 'update')->name('api.doctor-schedule-date.update');
        Route::delete($doctorScheduleDate, 'destroy')->name('api.doctor-schedule-date.delete');
    });
    Route::controller(ApiOutletController::class)->prefix('/outlet')->group(function () {
        Route::get('partner-equal-filter', 'partnerEqualFilter')->name('api.outlet.partner-equal-filter');
        $outlet = '{outlet}';
        Route::get('', 'index')->name('api.outlet.list');
        Route::get('name-id', 'nameId')->name('api.outlet.name.id');
        Route::post('', 'store')->name('api.outlet.create');
        Route::get($outlet, 'show')->name('api.outlet.show');
        Route::patch($outlet, 'update')->name('api.outlet.update');
        Route::delete($outlet, 'destroy')->name('api.outlet.delete');
        Route::get('generate-schedule/'.$outlet, 'generateSchedule')->name('api.outlet.generate-schedule');
    });
    Route::controller(ApiOutletScheduleController::class)->prefix('/outlet-schedule')->group(function () {
        Route::get('update', 'update')->name('api.outlet-schedule.update');
    });
    Route::controller(ApiProductController::class)->prefix('/product')->group(function () {
        $product = '{product}';
        Route::get('', 'index')->name('api.product.index');
        Route::get('list', 'list')->name('api.product.list');
        Route::post('', 'store')->name('api.product.create');
        Route::get($product, 'show')->name('api.product.show');
        Route::patch($product, 'update')->name('api.product.update');
        Route::delete($product, 'destroy')->name('api.product.delete');
    });
    Route::controller(ApiProductPackageController::class)->prefix('/product_package')->group(function () {
        $product = '{product_package}';
        Route::get('', 'index')->name('api.product_package.index');
        Route::get('list', 'list')->name('api.product_package.list');
        Route::post('', 'store')->name('api.product_package.create');
        Route::get($product, 'show')->name('api.product_package.show');
        Route::patch($product, 'update')->name('api.product_package.update');
        Route::delete($product, 'destroy')->name('api.product_package.delete');
    });
    Route::controller(ApiProductTrendingController::class)->prefix('/product_trending')->group(function () {
        Route::get('', 'index')->name('api.product_trending.index');
        Route::post('', 'update')->name('api.product_trending.update');
    });
    Route::controller(ApiProductFinestController::class)->prefix('/product_finest')->group(function () {
        Route::get('', 'index')->name('api.product_finest.index');
        Route::post('', 'update')->name('api.product_finest.update');
    });
    Route::controller(ApiOfficialPartnerController::class)->prefix('/official_partner')->group(function () {
        Route::get('', 'index')->name('api.official_partner.index');
        Route::post('', 'update')->name('api.official_partner.update');
    });
    Route::controller(ApiOfficialPartnerHomeController::class)->prefix('/official_partner_home')->group(function () {
        Route::get('', 'index')->name('api.official_partner_home.index');
        Route::post('', 'update')->name('api.official_partner_home.update');
    });
    Route::controller(ApiArticleRecommendationController::class)->prefix('/article_recommendation')->group(function () {
        Route::get('', 'index')->name('api.article_recommendation.index');
        Route::post('', 'update')->name('api.article_recommendation.update');
    });

    Route::controller(ApiContactOfficialController::class)->prefix('/contact_official')->group(function () {
        Route::get('', 'index')->name('api.contact_official.index');
        Route::post('', 'update')->name('api.contact_official.update');
    });
    
    Route::controller(ApiContactMessageController::class)->prefix('/contact_message')->group(function () {
        $contact_message = '{contact_message}';
        Route::get('', 'index')->name('api.contact_message.index');
        Route::get('list', 'list')->name('api.contact_message.list');
        Route::get($contact_message, 'show')->name('api.contact_message.show');
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
    Route::controller(ApiCustomerController::class)->prefix('/customer')->group(function () {
        $customer = '{customer}';
        Route::get('', 'index')->name('api.customer.list');
        Route::post('', 'store')->name('api.customer.create');
        Route::get($customer, 'show')->name('api.customer.show');
        Route::patch($customer, 'update')->name('api.customer.update');
        Route::delete($customer, 'destroy')->name('api.customer.delete');
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
    Route::controller(ApiBannerClinicController::class)->prefix('/banner_clinic')->group(function () {
        $banner_clinic = '{banner_clinic}';
        Route::get('', 'index')->name('api.banner_clinic.list');
        Route::post('', 'store')->name('api.banner_clinic.create');
        Route::get('list', 'list')->name('api.banner_clinic.list');
        Route::get($banner_clinic, 'show')->name('api.banner_clinic.show');
        Route::patch($banner_clinic, 'update')->name('api.banner_clinic.update');
        Route::delete($banner_clinic, 'destroy')->name('api.banner_clinic.delete');
    });
});

Route::get('test-api', function () {
    Log::info("test log info");
    return "ok";
});
