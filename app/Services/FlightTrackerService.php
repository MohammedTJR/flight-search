<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Seguimiento de un vuelo por su número, con fuentes públicas y gratuitas:
 *  - adsbdb.com: convierte el número IATA (VY3015) en indicativo ICAO (VLG3015)
 *    y da la ruta habitual (origen y destino).
 *  - adsb.lol: aviones en el aire en este momento (matrícula, tipo, posición, altitud...),
 *    buscados por indicativo o, si el vuelo no está en el aire, por la matrícula
 *    que se vio la última vez operándolo.
 *
 * Devuelve los datos con la misma forma que usaba la vista de "Mis vuelos".
 */
class FlightTrackerService
{
    private const ADSBDB = 'https://api.adsbdb.com/v0';
    private const ADSB_LOL = 'https://api.adsb.lol/v2';

    /**
     * @param  string $flightNumber Número IATA sin espacios (p. ej. VY3015)
     * @param  array  $previous     flight_data guardada antes (para reutilizar la matrícula)
     * @return array|null           null si no se reconoce el vuelo o falla la consulta
     */
    public function lookup(string $flightNumber, array $previous = []): ?array
    {
        $route = $this->route($flightNumber);

        // Si adsbdb no conoce el vuelo, se intenta el indicativo ICAO a partir de la aerolínea
        $callsign = $route['callsign_icao'] ?? $this->guessCallsign($flightNumber);

        $aircraft = $callsign ? $this->liveByCallsign($callsign) : null;
        $viaRegistration = false;

        // El vuelo no está en el aire: se busca el avión que lo operó la última vez
        if (!$aircraft && !empty($previous['reg'])) {
            $aircraft = $this->liveByRegistration($previous['reg']);
            $viaRegistration = (bool) $aircraft;
        }

        if (!$route && !$aircraft && empty($previous)) {
            return null;
        }

        $flightData = array_merge($previous, array_filter([
            'callsign' => $callsign ?? ($previous['callsign'] ?? null),
            'operating_as' => $route['airline']['name'] ?? null,
            'orig_icao' => $route['origin']['icao_code'] ?? null,
            'orig_iata' => $route['origin']['iata_code'] ?? null,
            'orig_name' => $route['origin']['municipality'] ?? $route['origin']['name'] ?? null,
            'dest_icao' => $route['destination']['icao_code'] ?? null,
            'dest_iata' => $route['destination']['iata_code'] ?? null,
            'dest_name' => $route['destination']['municipality'] ?? $route['destination']['name'] ?? null,
        ], fn($v) => $v !== null));

        $positionData = null;

        if ($aircraft) {
            // Matrícula y tipo del avión visto ahora; se recuerdan para la próxima vez
            $flightData['reg'] = $aircraft['r'] ?? ($previous['reg'] ?? null);
            $flightData['type'] = $aircraft['t'] ?? ($previous['type'] ?? null);
            $flightData['via_registration'] = $viaRegistration;
            $flightData['current_callsign'] = trim($aircraft['flight'] ?? '') ?: null;

            if (isset($aircraft['lat'], $aircraft['lon'])) {
                $onGround = ($aircraft['alt_baro'] ?? null) === 'ground';
                $seen = (float) ($aircraft['seen_pos'] ?? 0);

                $positionData = [
                    'lat' => $aircraft['lat'],
                    'lon' => $aircraft['lon'],
                    'alt' => $onGround ? 0 : (int) ($aircraft['alt_baro'] ?? $aircraft['alt_geom'] ?? 0),
                    'gspeed' => (int) round($aircraft['gs'] ?? 0),
                    'track' => (int) round($aircraft['track'] ?? 0),
                    'on_ground' => $onGround,
                    'timestamp' => now()->subSeconds((int) $seen)->toIso8601String(),
                ];
            }
        } else {
            $flightData['via_registration'] = false;
        }

        return [
            'flight_data' => $flightData,
            'position_data' => $positionData,
            'live' => $aircraft !== null,
        ];
    }

    /** Ruta habitual de un vuelo según adsbdb (incluye el indicativo ICAO). */
    private function route(string $flightNumber): ?array
    {
        return Cache::remember("tracker_route_{$flightNumber}", 21600, function () use ($flightNumber) {
            try {
                $response = Http::timeout(10)->get(self::ADSBDB . "/callsign/{$flightNumber}");
                if ($response->successful()) {
                    return $response->json('response.flightroute');
                }
            } catch (\Exception $e) {
                Log::warning('adsbdb no disponible: ' . $e->getMessage());
            }

            return null;
        });
    }

    /** Indicativo ICAO probable: código ICAO de la aerolínea + número de vuelo. */
    private function guessCallsign(string $flightNumber): ?string
    {
        if (!preg_match('/^([A-Z0-9]{2})(\d{1,4}[A-Z]?)$/', $flightNumber, $m)) {
            return null;
        }

        $icao = Cache::remember("tracker_airline_{$m[1]}", 86400 * 30, function () use ($m) {
            try {
                $response = Http::timeout(10)->get(self::ADSBDB . "/airline/{$m[1]}");
                if ($response->successful()) {
                    return $response->json('response.0.icao');
                }
            } catch (\Exception $e) {
                Log::warning('adsbdb no disponible: ' . $e->getMessage());
            }

            return null;
        });

        return $icao ? $icao . ltrim($m[2], '0') : null;
    }

    private function liveByCallsign(string $callsign): ?array
    {
        return $this->live("/callsign/{$callsign}");
    }

    private function liveByRegistration(string $registration): ?array
    {
        return $this->live('/reg/' . $registration);
    }

    /** Primer avión con posición que devuelva adsb.lol para esa consulta. */
    private function live(string $path): ?array
    {
        try {
            $response = Http::timeout(10)->get(self::ADSB_LOL . $path);

            if ($response->successful()) {
                foreach ($response->json('ac') ?? [] as $aircraft) {
                    if (isset($aircraft['lat'], $aircraft['lon'])) {
                        return $aircraft;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('adsb.lol no disponible: ' . $e->getMessage());
        }

        return null;
    }
}
