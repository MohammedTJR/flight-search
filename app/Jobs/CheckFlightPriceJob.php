<?php

namespace App\Jobs;

use App\Models\FavoriteFlight;
use App\Notifications\PriceChangeNotification;
use App\Services\ApiKeyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckFlightPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $favoriteFlight;

    /**
     * Create a new job instance.
     */
    public function __construct(FavoriteFlight $favoriteFlight)
    {
        $this->favoriteFlight = $favoriteFlight;
    }

    /**
     * Execute the job.
     */
    public function handle(ApiKeyService $apiKeyService)
    {
        // Verificar si el usuario quiere recibir alertas de precio
        if (!$this->favoriteFlight->user->notification_preferences['price_alerts']) {
            return;
        }

        $apiKey = $apiKeyService->getValidApiKey();
        if (!$apiKey) {
            Log::error('No valid API key available for price check');
            return;
        }

        $searchParams = $this->favoriteFlight->search_params;
        
        // Aplicar preferencias de viaje del usuario
        $userPreferences = $this->favoriteFlight->user->travel_preferences;
        $searchParams['travel_class'] = $userPreferences['preferred_class'];
        
        $url = "https://serpapi.com/search.json?"
            . "engine=google_flights"
            . "&departure_id={$this->favoriteFlight->origin}"
            . "&arrival_id={$this->favoriteFlight->destination}"
            . "&outbound_date={$this->favoriteFlight->departure_date->format('Y-m-d')}"
            . "&type=2"
            . "&currency=EUR"
            . "&hl=es"
            . "&adults={$searchParams['adults']}"
            . "&children={$searchParams['children']}"
            . "&infants_in_seat={$searchParams['infants_in_seat']}"
            . "&infants_on_lap={$searchParams['infants_on_lap']}"
            . "&travel_class={$searchParams['travel_class']}"
            . "&api_key={$apiKey}";

        // Añadir preferencia de vuelos directos si está activada
        if ($userPreferences['direct_flights']) {
            $url .= "&max_stops=0";
        }

        try {
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                $allFlights = array_merge($data['best_flights'] ?? [], $data['other_flights'] ?? []);
                
                foreach ($allFlights as $flight) {
                    if (
                        isset($flight['flights'][0]['flight_number']) &&
                        $flight['flights'][0]['flight_number'] == $this->favoriteFlight->flight_id &&
                        $flight['flights'][0]['airline'] == $this->favoriteFlight->airline
                    ) {
                        $newPrice = $flight['price'];
                        $oldPrice = $this->favoriteFlight->price;
                        
                        if ($newPrice != $oldPrice) {
                            // Actualizar el precio
                            $this->favoriteFlight->price = $newPrice;
                            $this->favoriteFlight->save();
                            
                            // Verificar frecuencia de notificación
                            $alertFrequency = $this->favoriteFlight->user->notification_preferences['alert_frequency'];
                            
                            // Solo notificar si es inmediato o si corresponde según la frecuencia
                            if ($this->shouldNotify($alertFrequency)) {
                                $this->favoriteFlight->user->notify(
                                    new PriceChangeNotification(
                                        $this->favoriteFlight,
                                        $oldPrice,
                                        $newPrice
                                    )
                                );
                            }
                        }
                        break;
                    }
                }
            } else {
                if ($response->status() === 429) {
                    $apiKeyService->markKeyAsInvalid($apiKey);
                    $this->release(60);
                }
                Log::error("Error checking flight price: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Exception checking flight price: " . $e->getMessage());
            $this->release(300);
        }
    }

    /**
     * Determina si se debe enviar una notificación según la frecuencia configurada
     */
    private function shouldNotify(string $frequency): bool
    {
        switch ($frequency) {
            case 'immediate':
                return true;
            case 'daily':
                $lastNotified = cache()->get("last_price_notification_{$this->favoriteFlight->id}");
                if (!$lastNotified || now()->diffInHours($lastNotified) >= 24) {
                    cache()->put("last_price_notification_{$this->favoriteFlight->id}", now(), now()->addDay());
                    return true;
                }
                return false;
            case 'weekly':
                $lastNotified = cache()->get("last_price_notification_{$this->favoriteFlight->id}");
                if (!$lastNotified || now()->diffInDays($lastNotified) >= 7) {
                    cache()->put("last_price_notification_{$this->favoriteFlight->id}", now(), now()->addWeek());
                    return true;
                }
                return false;
            default:
                return true;
        }
    }
}
