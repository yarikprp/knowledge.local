<?php

use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\News\NewsTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('news', '/news/news');

    Route::get('/news/newsList', fn () => redirect('/news/news/list'));
    // --- News ---
    Route::prefix('news/news')->name('news.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/list', [NewsController::class, 'list'])->name('list');
        Route::post('/', [NewsController::class, 'store'])->name('store');
        Route::put('/{news}', [NewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [NewsController::class, 'destroy'])->name('destroy');
        Route::get('/{news}', [NewsController::class, 'show'])->name('show');
    });

    // --- News Types ---
    Route::prefix('news/type')->name('type.')->group(function () {
        Route::get('/', [NewsTypeController::class, 'index'])->name('index');
        Route::get('/{type}/edit', [NewsTypeController::class, 'edit'])->name('edit');
        Route::post('/', [NewsTypeController::class, 'store'])->name('store');
        Route::put('/{type}', [NewsTypeController::class, 'update'])->name('update');
        Route::delete('/{type}', [NewsTypeController::class, 'destroy'])->name('destroy');
    });
});

