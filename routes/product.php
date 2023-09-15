<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
    Route::post('list', [ProductController::class, 'list'])->name('product.list');
    Route::get('create', [ProductController::class, 'create'])->name('product.create');
    Route::get('detail/{id}', [ProductController::class, 'show'])->name('product.detail');
    Route::post('store', [ProductController::class, 'store'])->name('product.store');
    Route::get('delete/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
    Route::post('update/{id}', [ProductController::class, 'update'])->name('product.update');
});
