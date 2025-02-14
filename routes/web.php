<?php

use App\Http\Controllers\AirportsController;
use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'index']);
Route::get('/flights', [FlightController::class, 'search'])->name('flights');
Route::get('/airports', [AirportsController::class, 'airports']);
Route::get('/api/search-airports', [AirportsController::class, 'searchAirports']);

Route::get('/vuelo/{id}', [FlightController::class, 'show'])->name('flight.show');
