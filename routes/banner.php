<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;


Route::prefix('banner')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('banner.index');
    Route::get('detail/{id}', [BannerController::class, 'show'])->name('banner.detail');
    Route::post('update/{id}', [BannerController::class, 'update'])->name('banner.update');
});
