<?php

use App\Http\Controllers\Rest\StatusController;
use App\Http\Controllers\Rest\EventTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // --- Status ---
    Route::prefix('status')->name('status.')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::put('/{status}', [StatusController::class, 'update'])->name('update');
        Route::delete('/{status}', [StatusController::class, 'destroy'])->name('destroy');
    });

    // --- EventType ---
    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventTypeController::class, 'index'])->name('index');
        Route::post('/', [EventTypeController::class, 'store'])->name('store');
        Route::put('/{event}', [EventTypeController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventTypeController::class, 'destroy'])->name('destroy');
    });
});

