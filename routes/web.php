<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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
})->name('login');

Route::post('login', [Controller::class, 'login']);
// Route::get('home', [Controller::class, 'getHome'])->middleware('validate_session');
Route::get('home', [Controller::class, 'getHome']);;
