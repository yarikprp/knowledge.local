<?php

use App\Http\Controllers\Education\SubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/subject', fn () => redirect('/subject/index'));

    // --- Subject Types ---
    Route::prefix('subject')->name('subject.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::post('/', [SubjectController::class, 'store'])->name('store');
        Route::put('/{subject}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
    });
});

