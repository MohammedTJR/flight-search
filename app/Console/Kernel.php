<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Use the command signature instead of the class name
        $schedule->command('flights:check-prices')
                ->name('Check Flight Prices')  // Add a readable name
                ->everyFourHours()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/price-checks.log'))
                ->before(function () {
                    Log::info('Iniciando verificación de precios');
                })
                ->after(function () {
                    Log::info('Verificación de precios completada');
                });

        // Ejecutar cada hora
        $schedule->command('favorites:delete-expired')
                ->hourly()
                ->appendOutputTo(storage_path('logs/favorites-cleanup.log'))
                ->before(function () {
                    Log::info('Iniciando limpieza de favoritos');
                })
                ->after(function () {
                    Log::info('Limpieza de favoritos completada');
                });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     */
    protected function scheduleTimezone(): string
    {
        return 'Europe/Madrid';
    }
}