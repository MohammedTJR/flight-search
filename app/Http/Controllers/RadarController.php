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
    public function getFlights(Request $request)
    {
        // Bounding box del área visible enviado por el cliente (con valores por
        // defecto para España). Se recorta a los rangos válidos del mundo.
        $lamin = max(-90.0, min(90.0, (float) $request->query('lamin', 35.0)));
        $lamax = max(-90.0, min(90.0, (float) $request->query('lamax', 44.0)));
        $lomin = max(-180.0, min(180.0, (float) $request->query('lomin', -10.0)));
        $lomax = max(-180.0, min(180.0, (float) $request->query('lomax', 5.0)));

        // Clave de caché por zona (redondeada) para agrupar peticiones similares
        // y respetar el límite de la API de OpenSky.
        $key = 'opensky_' . implode('_', array_map(
            fn ($v) => round($v, 1),
            [$lamin, $lamax, $lomin, $lomax]
        ));

        return Cache::remember($key, 30, function () use ($lamin, $lamax, $lomin, $lomax) {
            try {
                $response = Http::timeout(15)->get('https://opensky-network.org/api/states/all', [
                    'lamin' => $lamin,
                    'lamax' => $lamax,
                    'lomin' => $lomin,
                    'lomax' => $lomax,
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
     * Obtener la ruta (origen/destino) de un vuelo a partir de su callsign,
     * usando la base de datos pública de rutas adsbdb.com. Se cachea 6h.
     */
    public function getRoute($callsign)
    {
        $callsign = strtoupper(trim($callsign));

        if ($callsign === '') {
            return response()->json(['route' => null]);
        }

        $route = Cache::remember('route_' . $callsign, 21600, function () use ($callsign) {
            try {
                $resp = Http::timeout(10)->get("https://api.adsbdb.com/v0/callsign/{$callsign}");

                if ($resp->successful()) {
                    $fr = $resp->json()['response']['flightroute'] ?? null;
                    if ($fr && isset($fr['origin'], $fr['destination'])) {
                        return [
                            'origin' => $this->mapAirport($fr['origin']),
                            'destination' => $this->mapAirport($fr['destination']),
                        ];
                    }
                }

                return null;
            } catch (\Exception $e) {
                return null;
            }
        });

        return response()->json(['route' => $route]);
    }

    /**
     * Normaliza los datos de un aeropuerto devueltos por adsbdb.
     */
    private function mapAirport(array $a): array
    {
        return [
            'name' => $a['name'] ?? null,
            'iata' => $a['iata_code'] ?? null,
            'icao' => $a['icao_code'] ?? null,
            'municipality' => $a['municipality'] ?? null,
            'country' => $a['country_iso_name'] ?? null,
            'lat' => $a['latitude'] ?? null,
            'lng' => $a['longitude'] ?? null,
        ];
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