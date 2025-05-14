<?php

use App\Http\Controllers\Rest\StatusController;
use App\Http\Controllers\Rest\EventTypeController;
use App\Http\Controllers\Rest\CalendarController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // --- Status ---
    Route::prefix('status')->name('status.')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::put('/{status}', [StatusController::class, 'update'])->name('update');
        Route::delete('/{status}', [StatusController::class, 'destroy'])->name('destroy');
    });

    // --- EventType ---
    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventTypeController::class, 'index'])->name('index');
        Route::post('/', [EventTypeController::class, 'store'])->name('store');
        Route::put('/{event}', [EventTypeController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventTypeController::class, 'destroy'])->name('destroy');
    });

    // --- Calendar ---
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::post('/', [CalendarController::class, 'store'])->name('store');
        Route::post('/{id}/send-to-telegram', [CalendarController::class, 'sendToTelegram'])
            ->name('sendToTelegram');
        Route::put('/{calendar}', [CalendarController::class, 'update'])->name('update');
        Route::delete('/{calendar}', [CalendarController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-as-notified', [CalendarController::class, 'markAsNotified'])->name('markAsNotified');
    });

    Route::prefix('calendars')->name('calendars.')->group(function () {
        Route::get('/', [CalendarController::class, 'calendars'])->name('calendars');
        Route::post('/', [CalendarController::class, 'store'])->name('store');
        Route::put('/{calendars}', [CalendarController::class, 'update'])->name('update');
        Route::delete('/{calendars}', [CalendarController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('test-calendar')->name('test-calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'calendarsForUser'])->name('forUser');
        Route::post('/{id}/mark-as-notified', [CalendarController::class, 'markAsNotified'])->name('markAsNotified');
    });
});

