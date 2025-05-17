<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check prices every 4 hours
        $schedule->command('flights:check-prices')
                ->everyFourHours()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/price-checks.log'));
    }

    // ...existing code...
}