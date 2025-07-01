<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index')->middleware('can:role-list');

    Route::resource('users', UserController::class)->except(['index'])->middleware('can:user-edit');
    Route::resource('roles', RoleController::class)->except(['index', 'show'])->middleware('can:role-edit');
    Route::resource('permissions', PermissionController::class)->except(['index', 'show'])->middleware('can:permission-edit');
    Route::resource('offers', OfferController::class);
    Route::resource('buses', BusController::class);
    Route::get('customers', [UserController::class, 'customers'])->name('users.customers');

    // Booking flow routes
    Route::get('booking/search', [BookingController::class, 'search'])->name('booking.search');
    Route::get('booking/bus/{busId}', [BookingController::class, 'showBus'])->name('booking.showBus');
    Route::get('booking/bus/{busId}/seats', [BookingController::class, 'selectSeats'])->name('booking.selectSeats');
    Route::post('booking/bus/{busId}/passenger-info', [BookingController::class, 'passengerInfo'])->name('booking.passengerInfo');
    Route::post('booking/bus/{busId}/store', [BookingController::class, 'store'])->name('booking.store');
    Route::resource('bookings', BookingController::class)->only(['index', 'update', 'destroy', 'show']);
});

Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleLoginController::class, 'handleGoogleCallback']);

require __DIR__ . '/auth.php';
