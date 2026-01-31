<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CheckInController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public events routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Event management routes (organizer)
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    // Registration routes
    Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.my');

    // Check-in routes (organizer)
    Route::get('/events/{event}/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('/events/{event}/check-in/{registration}', [CheckInController::class, 'store'])->name('check-in.store');
    Route::get('/events/{event}/statistics', [CheckInController::class, 'statistics'])->name('check-in.statistics');
});

require __DIR__.'/auth.php';

// API endpoints for real-time updates
Route::get('/api/events/{event}/seats', [EventController::class, 'seatsAvailability']);
Route::get('/api/events/{event}/check-in-stats', [CheckInController::class, 'stats']);
