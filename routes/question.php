<?php

use App\Http\Controllers\Questions\QuestionTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/question/type', fn () => redirect('/question/type/index'));

    // --- Question Types ---
    Route::prefix('question/type')->name('type.')->group(function () {
        Route::get('/', [QuestionTypeController::class, 'index'])->name('index');
        Route::post('/', [QuestionTypeController::class, 'store'])->name('store');
        Route::put('/{questionType}', [QuestionTypeController::class, 'update'])->name('update');
        Route::delete('/{questionType}', [QuestionTypeController::class, 'destroy'])->name('destroy');
    });
});

