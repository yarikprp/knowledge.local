<?php

use App\Http\Controllers\Questions\QuestionController;
use App\Http\Controllers\Questions\QuestionTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/question', fn () => redirect('/question/index'));
    Route::get('/question/type', fn () => redirect('/question/type/index'));

    // --- Question ---
    Route::prefix('question')->name('question.')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::post('/', [QuestionController::class, 'store'])->name('store');
        Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
    });

    // --- Question Types ---
    Route::prefix('question/type')->name('types.')->group(function () {
        Route::get('/', [QuestionTypeController::class, 'index'])->name('index');
        Route::post('/', [QuestionTypeController::class, 'store'])->name('store');
        Route::put('/{questionType}', [QuestionTypeController::class, 'update'])->name('update');
        Route::delete('/{questionType}', [QuestionTypeController::class, 'destroy'])->name('destroy');
    });
});

