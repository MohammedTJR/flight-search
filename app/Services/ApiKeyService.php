<?php

namespace App\Services;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    private $keyRefreshInterval = 1800; // 30 minutos para refrescar estado

    public function getValidApiKey($service = 'serpapi')
    {
        // Primero intentar con la última clave válida usada
        $lastUsedKey = $this->getLastUsedKey($service);
        if ($lastUsedKey && $lastUsedKey->is_valid) {
            return $lastUsedKey->key;
        }

        // Obtener una clave válida no usada recientemente
        $validKey = ApiKey::valid()
            ->forService($service)
            ->orderBy('last_used_at', 'asc')
            ->first();

        if ($validKey) {
            $validKey->update(['last_used_at' => now()]);
            return $validKey->key;
        }

        // Si no hay claves válidas, intentar resetear las invalidadas
        $this->refreshInvalidKeys($service);

        // Intentar nuevamente después del refresco
        $validKey = ApiKey::valid()
            ->forService($service)
            ->orderBy('last_used_at', 'asc')
            ->first();

        if ($validKey) {
            $validKey->update(['last_used_at' => now()]);
            return $validKey->key;
        }

        Log::error("Todas las API keys para $service están agotadas o inválidas");
        return null;
    }

    public function markKeyAsInvalid($key, $service = 'serpapi')
    {
        $apiKey = ApiKey::where('key', $key)
            ->where('service', $service)
            ->first();

        if ($apiKey) {
            $apiKey->markAsInvalid();
        }
    }

    public function refreshInvalidKeys($service = 'serpapi')
    {
        $invalidKeys = ApiKey::forService($service)
            ->where('is_valid', false)
            ->get();

        foreach ($invalidKeys as $key) {
            if ($key->hasExpiredInvalidation()) {
                $key->markAsValid();
            }
        }
    }

    private function getLastUsedKey($service)
    {
        return ApiKey::forService($service)
            ->valid()
            ->orderBy('last_used_at', 'desc')
            ->first();
    }

    public function verifyKeyStatus($key, $service = 'serpapi')
    {
        try {
            if ($service === 'serpapi') {
                $response = Http::get("https://serpapi.com/account", [
                    'api_key' => $key
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $isValid = isset($data['plan_searches_left']) && $data['plan_searches_left'] > 0;

                    $apiKey = ApiKey::where('key', $key)->first();
                    if ($apiKey) {
                        $apiKey->update([
                            'is_valid' => $isValid,
                            'remaining_requests' => $data['plan_searches_left'] ?? null,
                            'invalid_at' => $isValid ? null : now()
                        ]);
                    }

                    return $isValid;
                }
            }

            // Puedes añadir verificaciones para otros servicios aquí

            return false;
        } catch (\Exception $e) {
            Log::error("Error al verificar API key: " . $e->getMessage());
            return false;
        }
    }

    public function initializeApiKeys()
    {
        // Solo ejecutar si la tabla está vacía
        if (ApiKey::count() > 0) {
            return;
        }

        $serpapiKeys = [
            env('SERPAPI_KEY'),
            env('SERPAPI_KEY_1'),
            env('SERPAPI_KEY_2'),
            env('SERPAPI_KEY_3'),
            env('SERPAPI_KEY_4'),
            env('SERPAPI_KEY_5'),
            env('SERPAPI_KEY_6'),
            env('SERPAPI_KEY_7'),
            env('SERPAPI_KEY_8'),
            env('SERPAPI_KEY_9'),
            env('SERPAPI_KEY_10'),
        ];

        $aviationstackKeys = [
            env('AVIATIONSTACK_KEY'),
            env('AVIATIONSTACK_KEY_1'),
            env('AVIATIONSTACK_KEY_2'),
            env('AVIATIONSTACK_KEY_3'),
            env('AVIATIONSTACK_KEY_4'),
            env('AVIATIONSTACK_KEY_5'),
            env('AVIATIONSTACK_KEY_6'),
            env('AVIATIONSTACK_KEY_7'),
            env('AVIATIONSTACK_KEY_8'),
            env('AVIATIONSTACK_KEY_9'),
            env('AVIATIONSTACK_KEY_10'),
        ];

        foreach (array_filter($serpapiKeys) as $key) {
            ApiKey::create([
                'key' => $key,
                'service' => 'serpapi',
                'is_valid' => true
            ]);
        }

        foreach (array_filter($aviationstackKeys) as $key) {
            ApiKey::create([
                'key' => $key,
                'service' => 'aviationstack',
                'is_valid' => true
            ]);
        }
    }
}