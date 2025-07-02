<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('offers', [OfferController::class, 'apiIndex']);
Route::post('/auth/google/callback', [GoogleLoginController::class, 'handleApiCallback']);
Route::get('buses', [BusController::class, 'apiIndex']);
Route::get('bus-cities', [BusController::class, 'cities']);
Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth:sanctum')->get('profile', [ProfileController::class, 'apiProfile']);
Route::middleware('auth:sanctum')->post('bookings', [BookingApiController::class, 'store']);
Route::get('buses/{id}', [BusController::class, 'apiShow']);
Route::get('buses-type/{type}', [BusController::class, 'apiByType']);
