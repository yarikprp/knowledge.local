<?php

use App\Http\Controllers\Test\TestTypeController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\Test\AttemptController;
use App\Http\Controllers\Test\AnswerController;
use App\Http\Controllers\Test\ResultController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/test', fn () => redirect('/test/index'));
    Route::get('/test/type', fn () => redirect('/test/type/index'));
    Route::get('/test/answer', fn () => redirect('/test/answer/index'));
    Route::get('/test/attempt', fn () => redirect('/test/attempt/index'));
    Route::get('/test/result', fn () => redirect('/test/result/index'));

    // --- Test ---
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        Route::get('/{test}', [TestController::class, 'show'])->name('show');
        Route::post('/', [TestController::class, 'store'])->name('store');
        Route::put('/{test}', [TestController::class, 'update'])->name('update');
        Route::delete('/{test}', [TestController::class, 'destroy'])->name('destroy');
        Route::put('/{test}', [TestController::class, 'update'])->name('update');
    });

    // --- Test Types ---
    Route::prefix('test/type')->name('type.')->group(function () {
        Route::get('/', [TestTypeController::class, 'index'])->name('index');
        Route::post('/', [TestTypeController::class, 'store'])->name('store');
        Route::put('/{testType}', [TestTypeController::class, 'update'])->name('update');
        Route::delete('/{testType}', [TestTypeController::class, 'destroy'])->name('destroy');
    });

    // --- Attempt ---
    Route::prefix('/test/attempt')->name('attempt.')->group(function () {
        Route::get('/', [AttemptController::class, 'index'])->name('index');
        Route::post('/', [AttemptController::class, 'store'])->name('store');
        Route::put('/{attempt}', [AttemptController::class, 'update'])->name('update');
        Route::delete('/{attempt}', [AttemptController::class, 'destroy'])->name('destroy');
    });

    // --- Answer ---
    Route::prefix('/test/answer')->name('answer.')->group(function () {
        Route::get('/', [AnswerController::class, 'index'])->name('index');
        Route::post('/', [AnswerController::class, 'store'])->name('store');
        Route::put('/{answer}', [AnswerController::class, 'update'])->name('update');
        Route::delete('/{answer}', [AnswerController::class, 'destroy'])->name('destroy');
    });

    // --- Answer ---
    Route::prefix('/test/result')->name('result.')->group(function () {
        Route::get('/', [ResultController::class, 'index'])->name('index');
        Route::get('/{result}', [ResultController::class, 'show'])->name('show');
    });
});

