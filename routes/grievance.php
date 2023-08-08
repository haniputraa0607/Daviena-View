<?php

use App\Http\Controllers\GrievanceControlller;
use Illuminate\Support\Facades\Route;


Route::prefix('grievance')->group(function () {
    Route::get('/', [GrievanceControlller::class, 'index'])->name('grievance.index');
    Route::get('create', [GrievanceControlller::class, 'create'])->name('grievance.create');
    Route::get('detail/{id}', [GrievanceControlller::class, 'show'])->name('grievance.detail');
    Route::post('store', [GrievanceControlller::class, 'store'])->name('grievance.store');
    Route::delete('delete/{id}', [GrievanceControlller::class, 'deleteGrievance'])->name('grievance.delete');
    Route::post('update/{id}', [GrievanceControlller::class, 'update'])->name('grievance.update');
});
