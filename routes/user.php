<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('create', [UserController::class, 'create'])->name('user.create');
    Route::get('detail/{id}', [UserController::class, 'show'])->name('user.detail');
    Route::post('store', [UserController::class, 'store'])->name('user.store');
    // Route::post('delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::post('update/{id}', [UserController::class, 'update'])->name('user.update');
});
