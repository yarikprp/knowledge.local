<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('admin', function () {
    return Inertia::render('Admin');
})->middleware(['auth', 'verified'])->name('admin');

require __DIR__.'/settings.php';
require __DIR__.'/news.php';
require __DIR__.'/auth.php';
require __DIR__.'/user.php';

Route::fallback(function () {
    return Inertia::render('errors/NotFound');
})->name('not-found');
