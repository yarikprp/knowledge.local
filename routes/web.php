<?php

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


Route::get('test', function () {
    return Inertia::render('Test');
})->middleware(['auth', 'verified'])->name('test');

require __DIR__.'/settings.php';
require __DIR__.'/news.php';
require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/question.php';
require __DIR__.'/test.php';
require __DIR__.'/education.php';

Route::fallback(function () {
    return Inertia::render('errors/NotFound');
})->name('not-found');
