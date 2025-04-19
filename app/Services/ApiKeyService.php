<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    private $apiKeys = [];

    private $cacheTTL = 86400;

    public function __construct()
    {
        $this->loadApiKeys();
    }

    /**
     * Carga todas las API keys disponibles desde la configuración
     */
    private function loadApiKeys()
    {
        // Obtener la clave principal
        $mainKey = env('SERPAPI_KEY');
        if ($mainKey) {
            $this->apiKeys[] = $mainKey;
        }

        // Obtener claves adicionales (SERPAPI_KEY_1, SERPAPI_KEY_2, etc.)
        $i = 1;
        while ($key = env("SERPAPI_KEY_{$i}")) {
            $this->apiKeys[] = $key;
            $i++;
        }

        if (empty($this->apiKeys)) {
            Log::error('No se encontraron API keys para SerpAPI');
        }
    }

    /**
     * Obtiene una API key válida
     * 
     * @return string|null
     */
    public function getValidApiKey()
    {
        // Intentar recuperar la última clave que funcionó
        $lastWorkingKey = Cache::get('serpapi_last_working_key');
        if ($lastWorkingKey && $this->isKeyValid($lastWorkingKey)) {
            return $lastWorkingKey;
        }

        // Si la última no funciona, rotar entre todas las claves
        foreach ($this->apiKeys as $key) {
            if ($this->isKeyValid($key)) {
                // Guardar esta clave como la última que funcionó
                Cache::put('serpapi_last_working_key', $key, $this->cacheTTL);
                return $key;
            }
        }

        // Si ninguna clave funciona, registrar el error y retornar null
        Log::error('Todas las API keys de SerpAPI están agotadas o inválidas');
        return null;
    }

    /**
     * Verifica si una API key es válida haciendo una petición simple
     * 
     * @param string $key
     * @return bool
     */
    private function isKeyValid($key)
    {
        // Verificar primero si la clave está marcada como inválida en la caché
        if (Cache::get("serpapi_invalid_key_{$key}")) {
            return false;
        }

        try {
            // Hacer una petición simple para verificar la validez de la clave
            $response = Http::get("https://serpapi.com/account", [
                'api_key' => $key
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Verificar si tiene llamadas disponibles
                if (isset($data['plan_searches_left']) && $data['plan_searches_left'] > 0) {
                    return true;
                } else {
                    // Marcar esta clave como inválida por un tiempo
                    Cache::put("serpapi_invalid_key_{$key}", true, 3600); // 1 hora
                    return false;
                }
            }

            Cache::put("serpapi_invalid_key_{$key}", true, 3600); // 1 hora
            return false;
        } catch (\Exception $e) {
            Log::error("Error al verificar API key: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marca una API key como inválida temporalmente
     * 
     * @param string $key
     * @return void
     */
    public function markKeyAsInvalid($key)
    {
        Cache::put("serpapi_invalid_key_{$key}", true, 3600); // 1 hora
    }
}