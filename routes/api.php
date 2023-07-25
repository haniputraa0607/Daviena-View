<?php

use App\Http\Controllers\Api\ApiOutletController;
use App\Http\Controllers\Api\ApiUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(ApiUserController::class)->prefix('/user')->group(function () {
    $user = '{user}';
    Route::get('', 'index')->name('user.list');
    Route::post('', 'store')->name('user.create');
    Route::get($user, 'show')->name('user.detail');
    Route::patch($user, 'update')->name('user.update');
    Route::delete($user, 'destroy')->name('user.delete');
});
Route::controller(ApiOutletController::class)->prefix('/outlet')->group(function () {
    $outlet = '{outlet}';
    Route::get('', 'index')->name('outlet.list');
    Route::post('', 'store')->name('outlet.create');
    Route::get($outlet, 'shown')->name('outlet.detail');
    Route::patch($outlet, 'update')->name('outlet.update');
    Route::delete($outlet, 'destroy')->name('outlet.delete');
});