<?php

use App\Http\Controllers\AirportsController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'index'])->name('home');
Route::get('/flights', [FlightController::class, 'search'])->name('flights');
Route::get('/airports', [AirportsController::class, 'airports']);
Route::get('/api/search-airports', [AirportsController::class, 'searchAirports']);

Route::get('/vuelo/{id}', [FlightController::class, 'show'])->name('flight.show');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rutas para Social Auth
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.login');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

    Route::post('/favorites', [FlightController::class, 'addFavorite'])->name('favorites.add');
    Route::delete('/favorites/{favoriteFlight}', [FlightController::class, 'removeFavorite'])->name('favorites.remove');
    Route::get('/favorites', [FlightController::class, 'showFavorites'])->name('favorites.show');

    Route::get('/favorites/{favoriteFlight}/details', [FlightController::class, 'showFavoriteDetails'])->name('favorites.details');
});