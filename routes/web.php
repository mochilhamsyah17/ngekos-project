<?php

use App\Http\Controllers\BoardingHouseController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

// booking
Route::get('/booking-check', [BookingController::class, 'index'])->name('booking.index');
Route::post('/booking-check', [BookingController::class, 'show'])->name('booking.show');

Route::get('/booking/{slug}', [BookingController::class, 'booking'])->name('booking.booking');
Route::get('/booking/{slug}/information', [BookingController::class, 'information'])->name('booking.information');
Route::post('/booking/{slug}/information/save', [BookingController::class, 'saveInformation'])->name('booking.saveInformation');

Route::get('/booking/{slug}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/{slug}/payment', [BookingController::class, 'payment'])->name('booking.payment');
Route::get('/booking-success', [BookingController::class, 'success'])->name('booking.success');

// find boarding house
Route::get('/find-boarding-house', [BoardingHouseController::class, 'find'])->name('boarding-house.find');
Route::get('/find-boarding-house/results', [BoardingHouseController::class, 'findResults'])->name('boarding-house.results');
Route::get('/boarding-house/{slug}', [BoardingHouseController::class, 'details'])->name('boarding-house.details');
Route::get('/boarding-hose/{slug}/rooms', [BoardingHouseController::class, 'rooms'])->name('boarding-house.rooms');

// category
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// city
Route::get('/city/{slug}', [CityController::class, 'show'])->name('city.show');
