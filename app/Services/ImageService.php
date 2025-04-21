<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ImageService
{
    public function getCityImage($city, $country = null)
    {
        // Definir una clave de caché única para esta ciudad/país
        $cacheKey = "city_image_{$city}_" . ($country ? $country : '');

        // Intenta obtener la imagen del caché primero (válida por 7 días)
        return Cache::remember($cacheKey, 60 * 24 * 7, function () use ($city, $country) {
            try {
                $searchTerm = $city;
                if ($country) {
                    $searchTerm .= " {$country} city";
                }

                $response = Http::get('https://api.unsplash.com/search/photos', [
                    'query' => $searchTerm,
                    'per_page' => 1,
                    'client_id' => env('UNSPLASH_ACCESS_KEY'),
                    'orientation' => 'landscape',
                ]);

                if ($response->successful() && !empty($response->json('results'))) {
                    // Devuelve la URL de la imagen pequeña para evitar cargar imágenes grandes
                    return $response->json('results.0.urls.small');
                }

                // Si no hay resultados o hay un error, devuelve una imagen predeterminada
                return asset('images/default-city.jpg');
            } catch (\Exception $e) {
                // En caso de error, devuelve una imagen predeterminada
                return asset('images/default-city.jpg');
            }
        });
    }
}