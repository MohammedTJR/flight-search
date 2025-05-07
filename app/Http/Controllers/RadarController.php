<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RadarController extends Controller
{
    /**
     * Mostrar vista del radar (web)
     */
    public function index()
    {
        return view('radar');
    }

    /**
     * Obtener datos de vuelos (API)
     */
    public function getFlights()
    {
        return Cache::remember('opensky_flights', 60, function () {
            try {
                $response = Http::timeout(15)->get('https://opensky-network.org/api/states/all', [
                    'lamin' => 35.0,
                    'lamax' => 44.0,
                    'lomin' => -10.0,
                    'lomax' => 5.0
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'states' => $data['states'] ?? []
                    ];
                }

                return ['states' => []];
            } catch (\Exception $e) {
                return [
                    'error' => $e->getMessage(),
                    'states' => []
                ];
            }
        });
    }

    /**
     * Obtener detalles de vuelo (API)
     */
    public function getFlightDetails($icao24)
    {
        try {
            $cached = Cache::get('opensky_flights');
            if ($cached) {
                foreach ($cached['states'] as $flight) {
                    if ($flight[0] === $icao24) {
                        return response()->json(['flight' => $flight]);
                    }
                }
            }

            return response()->json(['error' => 'Flight not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}