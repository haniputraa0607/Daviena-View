<?php

use App\Http\Controllers\DoctorScheduleDateController;
use Illuminate\Support\Facades\Route;


Route::prefix('doctor-schedule-date')->group(function () {
    Route::get('/', [DoctorScheduleDateController::class, 'index'])->name('doctor-schedule-date.index');
    Route::get('create', [DoctorScheduleDateController::class, 'create'])->name('doctor-schedule-date.create');
    Route::get('detail/{id}', [DoctorScheduleDateController::class, 'show'])->name('doctor-schedule-date.detail');
    Route::post('store', [DoctorScheduleDateController::class, 'store'])->name('doctor-schedule-date.store');
    Route::post('delete/{id}', [DoctorScheduleDateController::class, 'deleteUser'])->name('doctor-schedule-date.delete');
    Route::post('update/{id}', [DoctorScheduleDateController::class, 'update'])->name('doctor-schedule-date.update');
});
