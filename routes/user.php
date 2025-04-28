<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('user', '/user');

    Route::get('/user/userList', fn () => redirect('/user/index'));

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::post('/', [UserController::class, 'store'])->name('user');
        Route::get('{user}', [UserController::class, 'show'])->name('user');
        Route::put('{user}', [UserController::class, 'update'])->name('user');
        Route::delete('{user}', [UserController::class, 'destroy'])->name('user');
    });
});
