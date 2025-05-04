<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    private $apiKeys = [];
    private $cacheTTL = 3600; // 1 hora para claves inválidas
    private $keyRefreshInterval = 1800; // 30 minutos para refrescar estado

    public function __construct()
    {
        $this->loadApiKeys();
    }

    private function loadApiKeys()
    {
        $mainKey = env('SERPAPI_KEY');
        if ($mainKey) {
            $this->apiKeys[] = $mainKey;
        }

        $i = 1;
        while ($key = env("SERPAPI_KEY_{$i}")) {
            $this->apiKeys[] = $key;
            $i++;
        }

        if (empty($this->apiKeys)) {
            Log::error('No se encontraron API keys para SerpAPI');
        }
    }

    public function getValidApiKey()
    {
        // Primero intentar con la última clave que funcionó
        $lastWorkingKey = Cache::get('serpapi_last_working_key');
        if ($lastWorkingKey && !$this->isKeyMarkedAsInvalid($lastWorkingKey)) {
            return $lastWorkingKey;
        }

        // Rotar por todas las claves disponibles
        foreach ($this->apiKeys as $key) {
            if (!$this->isKeyMarkedAsInvalid($key)) {
                Cache::put('serpapi_last_working_key', $key, $this->keyRefreshInterval);
                return $key;
            }
        }

        // Si todas están marcadas como inválidas, intentar refrescar
        $this->refreshInvalidKeys();

        // Intentar nuevamente después del refresco
        foreach ($this->apiKeys as $key) {
            if (!$this->isKeyMarkedAsInvalid($key)) {
                Cache::put('serpapi_last_working_key', $key, $this->keyRefreshInterval);
                return $key;
            }
        }

        Log::error('Todas las API keys de SerpAPI están agotadas o inválidas');
        return null;
    }

    private function isKeyMarkedAsInvalid($key)
    {
        return Cache::has("serpapi_invalid_key_{$key}");
    }

    public function markKeyAsInvalid($key)
    {
        Cache::put("serpapi_invalid_key_{$key}", true, $this->cacheTTL);
    }

    public function refreshInvalidKeys()
    {
        foreach ($this->apiKeys as $key) {
            if ($this->isKeyMarkedAsInvalid($key)) {
                // Verificar si el tiempo de invalidación ha expirado
                $invalidatedAt = Cache::get("serpapi_invalid_key_{$key}_timestamp");

                if (!$invalidatedAt || now()->diffInMinutes($invalidatedAt) >= ($this->cacheTTL / 60)) {
                    Cache::forget("serpapi_invalid_key_{$key}");
                    Cache::forget("serpapi_invalid_key_{$key}_timestamp");
                }
            }
        }
    }

    /**
     * Verificación profunda del estado de una clave (solo cuando es necesario)
     */
    public function verifyKeyStatus($key)
    {
        try {
            $response = Http::get("https://serpapi.com/account", [
                'api_key' => $key
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['plan_searches_left']) && $data['plan_searches_left'] > 0;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Error al verificar API key: " . $e->getMessage());
            return false;
        }
    }
}