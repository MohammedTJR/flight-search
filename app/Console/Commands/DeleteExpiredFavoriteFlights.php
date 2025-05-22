<?php

namespace App\Console\Commands;

use App\Models\FavoriteFlight;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteExpiredFavoriteFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favorites:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete favorite flights that have already departed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = FavoriteFlight::where('departure_date', '<', Carbon::now())
            ->delete();

        $this->info("Se eliminaron {$count} vuelos favoritos expirados.");
    }
}
