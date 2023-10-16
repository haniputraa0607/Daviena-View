<?php

use App\Http\Controllers\Transactions\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('transaction.order.index');
    Route::get('detail/{id}', [OrderController::class, 'show'])->name('transaction.order.detail');
});
