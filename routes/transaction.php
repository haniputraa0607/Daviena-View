<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transactions\Settings\PaymentController;
use App\Http\Controllers\Transactions\Settings\PriceSettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;

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

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('create', [UserController::class, 'create'])->name('user.create');
    Route::get('detail/{id}', [UserController::class, 'show'])->name('user.detail');
    Route::post('store', [UserController::class, 'store'])->name('user.store');
    Route::post('delete', [UserController::class, 'delete'])->name('user.delete');
    Route::post('update', [UserController::class, 'update'])->name('user.update');
});

Route::prefix('outlet')->group(function () {
    Route::get('/', [OutletController::class, 'index'])->name('outlet.index');
    Route::get('create', [OutletController::class, 'create'])->name('outlet.create');
    Route::get('detail/{id}', [OutletController::class, 'show'])->name('outlet.detail');
    Route::post('store', [OutletController::class, 'store'])->name('outlet.store');
    Route::get('delete/{id}', [OutletController::class, 'delete'])->name('outlet.delete');
    Route::post('update/{id}', [OutletController::class, 'update'])->name('outlet.update');
});
