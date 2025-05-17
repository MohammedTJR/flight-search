<?php

namespace App\Console\Commands;

use App\Jobs\CheckFlightPriceJob;
use App\Models\FavoriteFlight;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class CheckFavoritePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flights:check-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check prices for favorite flights and notify users about changes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $favorites = FavoriteFlight::with('user')->get();
        
        $this->info("Checking prices for {$favorites->count()} favorite flights...");
        
        foreach ($favorites as $favorite) {
            CheckFlightPriceJob::dispatch($favorite);
        }

        $this->info('Price check jobs have been dispatched successfully.');
        
        return Command::SUCCESS;
    }
}
