<?php

use App\Http\Controllers\Questions\QuestionController;
use App\Http\Controllers\Questions\QuestionTypeController;
use App\Http\Controllers\Questions\QuestionCorrectAnswerController;
use App\Http\Controllers\Questions\QuestionOptionController;
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

    // --- Question Correct Answers ---
    Route::prefix('question/correct-answer')->name('correctAnswers.')->group(function () {
        Route::get('/', [QuestionCorrectAnswerController::class, 'index'])->name('index');
        Route::post('/', [QuestionCorrectAnswerController::class, 'store'])->name('store');
        Route::put('/{questionCorrectAnswer}', [QuestionCorrectAnswerController::class, 'update'])->name('update');
        Route::delete('/{questionCorrectAnswer}', [QuestionCorrectAnswerController::class, 'destroy'])->name('destroy');
    });

    // --- Question Options ---
    Route::prefix('question/option')->name('options.')->group(function () {
        Route::get('/', [QuestionOptionController::class, 'index'])->name('index');
        Route::post('/', [QuestionOptionController::class, 'store'])->name('store');
        Route::put('/{questionOption}', [QuestionOptionController::class, 'update'])->name('update');
        Route::delete('/{questionOption}', [QuestionOptionController::class, 'destroy'])->name('destroy');
    });
});

