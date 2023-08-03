<?php

use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;


Route::prefix('treatment')->group(function () {
    Route::get('/', [TreatmentController::class, 'index'])->name('treatment.index');
    Route::post('list', [TreatmentController::class, 'list'])->name('treatment.list');
    Route::get('create', [TreatmentController::class, 'create'])->name('treatment.create');
    Route::get('detail/{id}', [TreatmentController::class, 'show'])->name('treatment.detail');
    Route::post('store', [TreatmentController::class, 'store'])->name('treatment.store');
    Route::delete('delete/{id}', [TreatmentController::class, 'deleteTreatment'])->name('treatment.delete');
    Route::post('update/{id}', [TreatmentController::class, 'update'])->name('treatment.update');
});
