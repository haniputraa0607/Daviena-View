<?php

use App\Http\Controllers\PartnerController;
use Illuminate\Support\Facades\Route;


Route::prefix('partner')->group(function () {
    Route::get('/', [PartnerController::class, 'index'])->name('partner.index');
    Route::get('create', [PartnerController::class, 'create'])->name('partner.create');
    Route::get('detail/{id}', [PartnerController::class, 'show'])->name('partner.detail');
    Route::post('store', [PartnerController::class, 'store'])->name('partner.store');
    Route::delete('delete/{id}', [PartnerController::class, 'deletePartner'])->name('partner.delete');
    Route::post('update/{id}', [PartnerController::class, 'update'])->name('partner.update');
});
