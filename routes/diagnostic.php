<?php

use App\Http\Controllers\DiagnosticController;
use Illuminate\Support\Facades\Route;


Route::prefix('diagnostic')->group(function () {
    Route::get('/', [DiagnosticController::class, 'index'])->name('diagnostic.index');
    Route::get('create', [DiagnosticController::class, 'create'])->name('diagnostic.create');
    Route::get('detail/{id}', [DiagnosticController::class, 'show'])->name('diagnostic.detail');
    Route::post('store', [DiagnosticController::class, 'store'])->name('diagnostic.store');
    Route::delete('delete/{id}', [DiagnosticController::class, 'deleteDiagnostic'])->name('diagnostic.delete');
    Route::post('update/{id}', [DiagnosticController::class, 'update'])->name('diagnostic.update');
});
