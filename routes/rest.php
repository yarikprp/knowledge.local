<?php

use App\Http\Controllers\Rest\StatusController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/status', fn () => redirect('/question/index'));

    // --- Status ---
    Route::prefix('status')->name('status.')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::put('/{status}', [StatusController::class, 'update'])->name('update');
        Route::delete('/{status}', [StatusController::class, 'destroy'])->name('destroy');
    });

});

