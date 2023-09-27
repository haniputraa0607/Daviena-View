<?php

use App\Http\Controllers\LandingPage\HomeController;
use App\Http\Controllers\LandingPage\OfficialPartnerController;
use App\Http\Controllers\LandingPage\ContactMessageController;
use App\Http\Controllers\LandingPage\ContactOfficialController;
use App\Http\Controllers\BannerClinicController;
use Illuminate\Support\Facades\Route;


Route::prefix('landing_page')->group(function () {
    Route::prefix('home')->group(function () {
        Route::prefix('treatment_and_consultation')->group(function () {
            Route::get('/', [HomeController::class, 'treatmentConsultation'])->name('landing_page.home.treatment_consultation.index');
            Route::post('/', [HomeController::class, 'treatmentConsultationUpdate'])->name('landing_page.home.treatment_consultation.update');     
        });
        Route::prefix('product_trending')->group(function(){
            Route::get('/', [HomeController::class, 'productTrending'])->name('landing_page.home.product_trending.index');
            Route::post('/', [HomeController::class, 'productTrendingUpdate'])->name('landing_page.home.product_trending.update');
        });
        Route::prefix('product_finest')->group(function(){
            Route::get('/', [HomeController::class, 'productFinest'])->name('landing_page.home.product_finest.index');
            Route::post('/', [HomeController::class, 'productFinestUpdate'])->name('landing_page.home.product_finest.update');
        });
        Route::prefix('official_partner')->group(function(){
            Route::get('/', [HomeController::class, 'officialPartner'])->name('landing_page.home.official_partner.index');
            Route::post('/', [HomeController::class, 'officialPartnerUpdate'])->name('landing_page.home.official_partner.update');
        });
        Route::prefix('article_recommendation')->group(function(){
            Route::get('/', [HomeController::class, 'articleRecommendation'])->name('landing_page.home.article_recommendation.index');
            Route::post('/', [HomeController::class, 'articleRecommendationUpdate'])->name('landing_page.home.article_recommendation.update');
        });
    });
    Route::prefix('official_partner')->group(function(){
        Route::get('/', [OfficialPartnerController::class, 'index'])->name('landing_page.official_partner');
        Route::post('/', [OfficialPartnerController::class, 'update'])->name('landing_page.official_partner.update');
    });
    Route::prefix('contact_message')->group(function(){
        Route::get('/', [ContactMessageController::class, 'index'])->name('landing_page.contact_message');
        Route::get('detail/{id}', [ContactMessageController::class, 'show'])->name('landing_page.contact_message.detail');
    });
    Route::prefix('contact_official')->group(function(){
        Route::get('/', [ContactOfficialController::class, 'index'])->name('landing_page.contact_official');
        Route::post('/', [ContactOfficialController::class, 'update'])->name('landing_page.contact_official.update');
    });
    Route::prefix('banner_clinic')->group(function () {
        Route::get('/', [BannerClinicController::class, 'index'])->name('banner_clinic.index');
        Route::post('list', [BannerClinicController::class, 'list'])->name('banner_clinic.list');
        Route::post('store', [BannerClinicController::class, 'store'])->name('banner_clinic.store');
        Route::delete('delete/{id}', [BannerClinicController::class, 'deleteBannerClinic'])->name('banner_clinic.delete');
        Route::post('update/{id}', [BannerClinicController::class, 'update'])->name('banner_clinic.update');
    });
});
