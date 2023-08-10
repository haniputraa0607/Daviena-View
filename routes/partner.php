<?php

use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerEqualController;
use Illuminate\Support\Facades\Route;


Route::prefix('partner')->group(function () {
    Route::get('/', [PartnerController::class, 'index'])->name('partner.index');
    Route::get('create', [PartnerController::class, 'create'])->name('partner.create');
    Route::get('detail/{id}', [PartnerController::class, 'show'])->name('partner.detail');
    Route::post('store', [PartnerController::class, 'store'])->name('partner.store');
    Route::delete('delete/{id}', [PartnerController::class, 'deletePartner'])->name('partner.delete');
    Route::post('update/{id}', [PartnerController::class, 'update'])->name('partner.update');
});

Route::prefix('partner_equal')->group(function () {
    Route::get('/', [PartnerEqualController::class, 'index'])->name('partner_equal.index');
    Route::get('create', [PartnerEqualController::class, 'create'])->name('partner_equal.create');
    Route::get('detail/{id}', [PartnerEqualController::class, 'show'])->name('partner_equal.detail');
    Route::post('store', [PartnerEqualController::class, 'store'])->name('partner_equal.store');
    Route::delete('delete/{id}', [PartnerEqualController::class, 'deletePartner'])->name('partner_equal.delete');
    Route::post('update/{id}', [PartnerEqualController::class, 'update'])->name('partner_equal.update');
});
