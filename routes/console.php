<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Laravel 11 ya no lee app/Console/Kernel.php: las tareas programadas se definen aquí.
// Las ejecuta `php artisan schedule:run`, que lanza el cron del servidor cada minuto.

// Comprueba el precio de los vuelos favoritos y avisa por correo si cambia
Schedule::command('flights:check-prices')
    ->name('Check Flight Prices')
    ->everyFourHours()
    ->timezone('Europe/Madrid')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/price-checks.log'))
    ->before(function () {
        Log::info('Iniciando verificación de precios');
    })
    ->after(function () {
        Log::info('Verificación de precios completada');
    });

// Borra los favoritos cuya fecha de salida ya ha pasado
Schedule::command('favorites:delete-expired')
    ->hourly()
    ->timezone('Europe/Madrid')
    ->appendOutputTo(storage_path('logs/favorites-cleanup.log'))
    ->before(function () {
        Log::info('Iniciando limpieza de favoritos');
    })
    ->after(function () {
        Log::info('Limpieza de favoritos completada');
    });
