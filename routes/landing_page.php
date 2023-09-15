<?php

use App\Http\Controllers\LandingPage\HomeController;
use App\Http\Controllers\LandingPage\OfficialPartnerController;
use App\Http\Controllers\LandingPage\ContactMessageController;
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
    });
    Route::prefix('official_partner')->group(function(){
        Route::get('/', [OfficialPartnerController::class, 'index'])->name('landing_page.official_partner');
        Route::post('/', [OfficialPartnerController::class, 'update'])->name('landing_page.official_partner.update');
    });
    Route::prefix('contact_message')->group(function(){
        Route::get('/', [ContactMessageController::class, 'index'])->name('landing_page.contact_message');
        Route::get('detail/{id}', [ContactMessageController::class, 'show'])->name('landing_page.contact_message.detail');
    });
});
