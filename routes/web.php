<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::get('logout', function () {
    session()->flush();
    return redirect('login');
});


Route::get('login', function () {
    if (!session()->has('user_id')) {
        return view('login');
    } else {
        return redirect('home');
    }
});

Route::post('login', [Controller::class, 'login']);
// Route::get('home', [Controller::class, 'getHome'])->middleware('validate_session');
Route::get('home', [Controller::class, 'getHome']);;

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('create', [UserController::class, 'create'])->name('user.create');
    Route::get('detail/{id}', [UserController::class, 'show'])->name('user.detail');
    Route::post('store', [UserController::class, 'store'])->name('user.store');
    Route::post('delete', [UserController::class, 'delete'])->name('user.delete');
    Route::post('update', [UserController::class, 'update'])->name('user.update');
});

Route::prefix('outlet')->group(function () {
    Route::get('/', [OutletController::class, 'index'])->name('outlet.index');
    Route::get('create', [OutletController::class, 'create'])->name('outlet.create');
    Route::get('detail/{id}', [OutletController::class, 'show'])->name('outlet.detail');
    Route::post('store', [OutletController::class, 'store'])->name('outlet.store');
    Route::get('delete/{id}', [OutletController::class, 'delete'])->name('outlet.delete');
    Route::post('update', [OutletController::class, 'update'])->name('outlet.update');
});
