<?php

namespace App\Console\Commands;

use App\Services\ApiKeyService;
use Illuminate\Console\Command;

class InitializeApiKeys extends Command
{
    protected $signature = 'api-keys:initialize';
    protected $description = 'Initialize API keys from .env to database';

    public function handle(ApiKeyService $apiKeyService)
    {
        $this->info('Initializing API keys...');
        $apiKeyService->initializeApiKeys();
        $this->info('API keys initialized successfully!');
    }
}