<?php

use App\Http\Controllers\Test\TestTypeController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\Questions\AttestationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('admin', function () {
    if (Gate::denies('is-admin', auth()->user())) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('Admin');
})->middleware(['auth', 'verified'])->name('admin');

Route::get('mentor', function () {
    if (Gate::denies('is-mentor', auth()->user())) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('Mentor');
})->middleware(['auth', 'verified'])->name('mentor');


Route::get('test-calendar', function () {
    return Inertia::render('TestCalendar');
})->middleware(['auth', 'verified'])->name('test-calendar');

Route::get('test/list', [TestController::class, 'list'])
    ->middleware(['auth', 'verified'])
    ->name('test');

Route::middleware('auth')->group(function () {
    Route::get('/attestation/{test}', [AttestationController::class, 'show'])->name('attestation.show');
    Route::post('/attestation/{test}/submit', [AttestationController::class, 'submit'])->name('attestation.submit');
});


require __DIR__.'/settings.php';
require __DIR__.'/news.php';
require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/question.php';
require __DIR__.'/test.php';
require __DIR__.'/education.php';
require __DIR__.'/rest.php';

Route::fallback(function () {
    return Inertia::render('errors/NotFound');
})->name('not-found');
