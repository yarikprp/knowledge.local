<?php

use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\News\NewsTypeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('news', '/news/news');

    // News
    Route::get('/news/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/news', [NewsController::class, 'index'])->name('news.show');
    Route::post('/news/news', [NewsController::class, 'store'])->name('news.store');
    Route::put('/news/news', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/news', [NewsController::class, 'destroy'])->name('news.destroy');

    // News Type
    Route::get('/news/type', [NewsTypeController::class, 'index'])->name('type.index');
    Route::get('/news/type', [NewsTypeController::class, 'edit'])->name('type.edit');
    Route::post('/news/type', [NewsTypeController::class, 'store'])->name('type.store');
    Route::put('/news/type', [NewsTypeController::class, 'update'])->name('type.update');
    Route::delete('/news/type', [NewsTypeController::class, 'destroy'])->name('type.destroy');
});
