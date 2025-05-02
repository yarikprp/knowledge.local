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
    return Inertia::render('Mentor');
})->middleware(['auth', 'verified'])->name('mentor');

require __DIR__.'/settings.php';
require __DIR__.'/news.php';
require __DIR__.'/auth.php';
require __DIR__.'/user.php';

Route::fallback(function () {
    return Inertia::render('errors/NotFound');
})->name('not-found');
