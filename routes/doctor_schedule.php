<?php

use App\Http\Controllers\DoctorScheduleController;
use Illuminate\Support\Facades\Route;


Route::prefix('doctor-schedule')->group(function () {
    Route::get('/', [DoctorScheduleController::class, 'index'])->name('doctor-schedule.index');
    Route::get('create', [DoctorScheduleController::class, 'create'])->name('doctor-schedule.create');
    Route::get('detail/{id}', [DoctorScheduleController::class, 'show'])->name('doctor-schedule.detail');
    Route::post('store', [DoctorScheduleController::class, 'store'])->name('doctor-schedule.store');
    Route::post('delete/{id}', [DoctorScheduleController::class, 'deleteUser'])->name('doctor-schedule.delete');
    Route::post('update/{id}', [DoctorScheduleController::class, 'update'])->name('doctor-schedule.update');
});
