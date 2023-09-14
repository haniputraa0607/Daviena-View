<?php

use App\Http\Controllers\ProductPackageController;
use Illuminate\Support\Facades\Route;


Route::prefix('product_package')->group(function () {
    Route::get('/', [ProductPackageController::class, 'index'])->name('product_package.index');
    Route::post('list', [ProductPackageController::class, 'list'])->name('product_package.list');
    Route::get('create', [ProductPackageController::class, 'create'])->name('product_package.create');
    Route::get('detail/{id}', [ProductPackageController::class, 'show'])->name('product_package.detail');
    Route::post('store', [ProductPackageController::class, 'store'])->name('product_package.store');
    Route::get('delete/{id}', [ProductPackageController::class, 'deleteProduct'])->name('product_package.delete');
    Route::post('update/{id}', [ProductPackageController::class, 'update'])->name('product_package.update');
});
