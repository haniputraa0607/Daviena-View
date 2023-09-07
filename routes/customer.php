<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('create', [CustomerController::class, 'create'])->name('customer.create');
    Route::get('detail/{id}', [CustomerController::class, 'show'])->name('customer.detail');
    Route::post('store', [CustomerController::class, 'store'])->name('customer.store');
    Route::post('delete/{id}', [CustomerController::class, 'deleteUser'])->name('customer.delete');
    Route::post('update/{id}', [CustomerController::class, 'update'])->name('customer.update');
});
