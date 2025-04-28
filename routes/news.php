<?php

use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\News\NewsTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('news', '/news');

    Route::get('/news/newsList', fn () => redirect('/news/list'));
    Route::get('/news/listView', fn () => redirect('/news/listView'));
    Route::get('/news/type', fn () => redirect('/news/type/index'));

    // --- News ---
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/list', [NewsController::class, 'list'])->name('list');
        Route::get('/listView', [NewsController::class, 'listView'])->name('listView');
        Route::post('/', [NewsController::class, 'store'])->name('store');
        Route::put('/{news}', [NewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [NewsController::class, 'destroy'])->name('destroy');
        Route::get('/{news}', [NewsController::class, 'show'])->name('show');
    });

    // --- News Types ---
    Route::prefix('news/type')->name('type.')->group(function () {
        Route::get('/', [NewsTypeController::class, 'index'])->name('index');
        Route::post('/', [NewsTypeController::class, 'store'])->name('store');
        Route::put('/{newsType}', [NewsTypeController::class, 'update'])->name('update');
        Route::delete('/{newsType}', [NewsTypeController::class, 'destroy'])->name('destroy');
    });
});

