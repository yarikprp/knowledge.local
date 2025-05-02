<?php

use App\Http\Controllers\Test\TestTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/test/type', fn () => redirect('/test/type/index'));

    // --- Test Types ---
    Route::prefix('test/type')->name('type.')->group(function () {
        Route::get('/', [TestTypeController::class, 'index'])->name('index');
        Route::post('/', [TestTypeController::class, 'store'])->name('store');
        Route::put('/{testType}', [TestTypeController::class, 'update'])->name('update');
        Route::delete('/{testType}', [TestTypeController::class, 'destroy'])->name('destroy');
    });
});

