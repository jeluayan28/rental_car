<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
Route::post('/cars/{car}/bookings', [BookingController::class, 'store'])->middleware(['auth', 'throttle:10,1'])->name('bookings.store');
Route::get('/bookings', [BookingController::class, 'index'])->middleware('auth')->name('bookings.index');
Route::get('/bookings/{booking}', [BookingController::class, 'show'])->middleware('auth')->name('bookings.show');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::patch('cars/{car}/status', [Admin\CarController::class, 'updateStatus'])->name('cars.status');
    Route::resource('cars', Admin\CarController::class);

    Route::get('bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/{action}', [Admin\BookingController::class, 'transition'])
        ->whereIn('action', ['confirm', 'complete', 'cancel'])->name('bookings.transition');

    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');

    Route::get('payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/paid', [Admin\PaymentController::class, 'markPaid'])->name('payments.paid');
});

require __DIR__.'/auth.php';
