<?php

use App\Http\Controllers\AirportsController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RadarController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

// Rutas para recuperación de contraseña
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

Route::get('/ayuda', function () {
    return view('help');
})->name('help');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/politica-cookies', function () {
    return view('partials.cookies-banner'); // Deberás crear esta vista
})->name('cookies.policy');

// Rutas para Social Auth
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.login');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback');

// Rutas de autenticación y verificación
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('verified', '¡Tu correo ha sido verificado!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '¡Link de verificación enviado!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Rutas protegidas que requieren verificación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

    Route::post('/favorites', [FlightController::class, 'addFavorite'])->name('favorites.add');
    Route::delete('/favorites/{favoriteFlight}', [FlightController::class, 'removeFavorite'])->name('favorites.remove');
    Route::get('/favorites', [FlightController::class, 'showFavorites'])->name('favorites.show');

    Route::get('/favorites/{favoriteFlight}/details', [FlightController::class, 'showFavoriteDetails'])->name('favorites.details');
});

Route::get('/radar', [RadarController::class, 'index'])->name('radar');

Route::prefix('api/radar')->group(function () {
    Route::get('/', [RadarController::class, 'getFlights']);
    Route::get('/{icao24}', [RadarController::class, 'getFlightDetails']);
});