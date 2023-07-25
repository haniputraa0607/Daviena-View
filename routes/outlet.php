<?php

use App\Http\Controllers\OutletController;
use Illuminate\Support\Facades\Route;

Route::prefix('outlet')->group(function () {
    Route::get('/', [OutletController::class, 'index'])->name('outlet.index');
    Route::get('create', [OutletController::class, 'create'])->name('outlet.create');
    Route::get('detail/{id}', [OutletController::class, 'show'])->name('outlet.detail');
    Route::post('store', [OutletController::class, 'store'])->name('outlet.store');
    // Route::get('delete/{id}', [OutletController::class, 'delete'])->name('outlet.delete');
    Route::post('update/{id}', [OutletController::class, 'update'])->name('outlet.update');
});