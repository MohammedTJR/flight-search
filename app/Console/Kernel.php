<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Ejecutar la comprobación de precios cada 6 horas
        $schedule->command('flights:check-prices')->everyFourHours();
    }

    // ...existing code...
}