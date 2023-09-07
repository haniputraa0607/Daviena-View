<?php

use App\Http\Controllers\DoctorShiftController;
use Illuminate\Support\Facades\Route;


Route::prefix('doctor-shift')->group(function () {
    Route::get('/', [DoctorShiftController::class, 'index'])->name('doctor-shift.index');
    Route::get('create', [DoctorShiftController::class, 'create'])->name('doctor-shift.create');
    Route::get('detail/{id}', [DoctorShiftController::class, 'show'])->name('doctor-shift.detail');
    Route::post('store', [DoctorShiftController::class, 'store'])->name('doctor-shift.store');
    Route::post('delete/{id}', [DoctorShiftController::class, 'deleteUser'])->name('doctor-shift.delete');
    Route::post('update/{id}', [DoctorShiftController::class, 'update'])->name('doctor-shift.update');
});
