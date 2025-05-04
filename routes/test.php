<?php

use App\Http\Controllers\Test\TestTypeController;
use App\Http\Controllers\Test\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/test', fn () => redirect('/test/index'));
    Route::get('/test/type', fn () => redirect('/test/type/index'));

    // --- Test ---
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        Route::post('/', [TestController::class, 'store'])->name('store');
        Route::put('/{test}', [TestController::class, 'update'])->name('update');
        Route::delete('/{test}', [TestController::class, 'destroy'])->name('destroy');
    });

    // --- Test Types ---
    Route::prefix('test/type')->name('type.')->group(function () {
        Route::get('/', [TestTypeController::class, 'index'])->name('index');
        Route::post('/', [TestTypeController::class, 'store'])->name('store');
        Route::put('/{testType}', [TestTypeController::class, 'update'])->name('update');
        Route::delete('/{testType}', [TestTypeController::class, 'destroy'])->name('destroy');
    });
});

