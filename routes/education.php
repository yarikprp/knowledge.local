<?php

use App\Http\Controllers\Education\SubjectController;
use App\Http\Controllers\Education\MaterialController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/subject', fn () => redirect('/subject/index'));
    Route::get('/subject', fn () => redirect('/subject/list'));
    Route::get('/subject', fn () => redirect('/subject/show'));
    Route::get('/material', fn () => redirect('/material/index'));

    // --- Subject  ---
    Route::prefix('subject')->name('subject.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/list', [SubjectController::class, 'list'])->name('list');
        Route::get('/{subject}', [SubjectController::class, 'show'])->name('show');
        Route::post('/', [SubjectController::class, 'store'])->name('store');
        Route::put('/{subject}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
    });

    // --- Material  ---
    Route::prefix('material')->name('material.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('destroy');
    });
});

