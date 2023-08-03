<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;


Route::prefix('article')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('article.index');
    Route::get('create', [ArticleController::class, 'create'])->name('article.create');
    Route::get('detail/{id}', [ArticleController::class, 'show'])->name('article.detail');
    Route::post('store', [ArticleController::class, 'store'])->name('article.store');
    Route::delete('delete/{id}', [ArticleController::class, 'deleteArticle'])->name('article.delete');
    Route::post('update/{id}', [ArticleController::class, 'update'])->name('article.update');
});
