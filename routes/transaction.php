<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transactions\Settings\PaymentController;
use App\Http\Controllers\Transactions\Settings\PriceSettingController;

Route::prefix('transaction')->middleware('validate_session')->group(function () {
    Route::prefix('setting')->group(function () {
        Route::get('/available-payment', [PaymentController::class, 'getAvailablePaymentSetting'])->middleware('feature_control:30');
        Route::middleware('log:activity')->group(function () {
            Route::post('/available-payment', [PaymentController::class, 'updateAvailablePaymentSetting'])->name('transaction.setting.payment.method.update')->middleware('feature_control:31');
            Route::post('/available-payment/update/logo', [PaymentController::class, 'updateLogoPaymentMethod'])->name('transaction.payment.method.logo.update')->middleware('feature_control:31');
        });

        // Price Setting
        Route::prefix('price')->group(function () {
            Route::get('/', [PriceSettingController::class, 'getPriceSetting']);
            Route::middleware('log:activity')->group(function () {
                Route::post('/global', [PriceSettingController::class, 'UpdateGlobalPrice'])->name('global.price.update'); //->middleware('feature_control:');
                Route::post('/mdr-fee', [PriceSettingController::class, 'UpdateMDRFee'])->name('mdr.fee.update'); //->middleware('feature_control:');
                Route::post('/custom-price/add', [PriceSettingController::class, 'AddCustomPrice'])->name('custom.price.add'); //->middleware('feature_control:');
                Route::post('/custom-price/update/{id}', [PriceSettingController::class, 'UpdateCustomPrice'])->name('custom.price.update'); //->middleware('feature_control:');
                Route::get('/custom-price/delete/{id}', [PriceSettingController::class, 'DeleteCustomPrice'])->name('custom.price.delete'); //->middleware('feature_control:');
            });
        });
    });


});
