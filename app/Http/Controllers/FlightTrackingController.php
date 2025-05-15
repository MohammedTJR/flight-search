<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\TrackedFlight;
use Illuminate\Support\Facades\Auth;
use Log;

class FlightTrackingController extends Controller
{
    private $apiKey = "0196d61f-a87a-707f-b87e-a785f0ac5002|87RjRvksNSOToN5Q8a8kLU4iln7PX1iNqamwJ59A0f7e5fb4";

    public function index()
    {
        $trackedFlights = Auth::user()->trackedFlights()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($flight) {
                if ($flight->position_data) {
                    $positionData = $flight->position_data; // Creamos una copia del array
                    $positionData['address'] = $this->getAddressFromCoordinates(
                        $flight->position_data['lat'],
                        $flight->position_data['lon']
                    );
                    $flight->position_data = $positionData; // Asignamos el array modificado
                }
                return $flight;
            });

        return view('flight-tracking.index', compact('trackedFlights'));
    }

    public function showFlightForm()
    {
        return view('flight-tracking.form');
    }

    public function trackFlight(Request $request)
    {
        $request->validate([
            'flight_number' => 'required|string|max:10'
        ]);

        $flightNumber = strtoupper(str_replace(' ', '', $request->flight_number));

        // Verificar si el usuario ya sigue este vuelo
        if (Auth::user()->trackedFlights()->where('flight_number', $flightNumber)->exists()) {
            return back()->with('error', 'Ya estás siguiendo este vuelo.');
        }

        $flightInfo = $this->getFlightInfo($flightNumber);

        if (empty($flightInfo['data'])) {
            return back()->with('error', 'No se encontró información para el vuelo ' . $flightNumber);
        }

        $registration = $flightInfo['data'][0]['reg'] ?? null;
        $flightPosition = $registration ? $this->getFlightPosition($registration) : null;

        // Guardar el vuelo seguido
        $trackedFlight = Auth::user()->trackedFlights()->create([
            'flight_number' => $flightNumber,
            'flight_data' => $flightInfo['data'][0],
            'position_data' => $flightPosition['data'][0] ?? null,
            'last_checked_at' => now()
        ]);

        return redirect()->route('flight.tracking.index')->with('success', 'Vuelo añadido a tu lista de seguimiento.');
    }

    public function refreshFlight($id)
    {
        $trackedFlight = Auth::user()->trackedFlights()->findOrFail($id);

        $flightInfo = $this->getFlightInfo($trackedFlight->flight_number);
        $registration = $flightInfo['data'][0]['reg'] ?? null;
        $flightPosition = $registration ? $this->getFlightPosition($registration) : null;

        $trackedFlight->update([
            'flight_data' => $flightInfo['data'][0] ?? $trackedFlight->flight_data,
            'position_data' => $flightPosition['data'][0] ?? $trackedFlight->position_data,
            'last_checked_at' => now()
        ]);

        return back()->with('success', 'Información del vuelo actualizada.');
    }

    public function destroy($id)
    {
        $trackedFlight = Auth::user()->trackedFlights()->findOrFail($id);
        $trackedFlight->delete();

        return back()->with('success', 'Vuelo eliminado de tu lista de seguimiento.');
    }

    private function getFlightInfo($flightNumber)
    {
        $today = Carbon::now()->format('Y-m-d\TH:i:s\Z');
        $twoWeeksAgo = Carbon::now()->subDays(13)->format('Y-m-d\TH:i:s\Z');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Version' => 'v1',
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get('https://fr24api.flightradar24.com/api/flight-summary/light', [
                    'flights' => $flightNumber,
                    'flight_datetime_from' => $twoWeeksAgo,
                    'flight_datetime_to' => $today
                ]);

        return $response->json();
    }

    private function getFlightPosition($registration)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Version' => 'v1',
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get('https://fr24api.flightradar24.com/api/live/flight-positions/light', [
                    'registrations' => $registration
                ]);

        return $response->json();
    }

    private function getAddressFromCoordinates($lat, $lon)
    {
        // Primero validamos las coordenadas
        if (!is_numeric($lat) || !is_numeric($lon)) {
            return 'Coordenadas inválidas';
        }

        try {
            $apiKey = 'pk.88b33c8a31452200a7ffd22cb8ddb2c2'; // Reemplaza con tu API key de LocationIQ

            $response = Http::retry(3, 100)->get('https://us1.locationiq.com/v1/reverse.php', [
                'key' => $apiKey,
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
                'accept-language' => 'es',
                'zoom' => 14,
                'addressdetails' => 1
            ]);

            Log::info('Response from LocationIQ', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return 'No se pudo obtener la ubicación (Código: ' . $response->status() . ')';
            }

            $data = $response->json();

            // LocationIQ usa 'address' igual que Nominatim
            if (!isset($data['address'])) {
                // Si no hay address pero hay display_name, lo usamos
                if (isset($data['display_name'])) {
                    return $data['display_name'];
                }
                return 'Ubicación desconocida';
            }

            $address = $data['address'];

            // Orden de prioridad para los componentes de la dirección
            $components = [
                'village',
                'town',
                'city',
                'municipality',
                'county',
                'state',
                'country'
            ];

            $locationParts = [];

            foreach ($components as $component) {
                if (!empty($address[$component])) {
                    $locationParts[] = $address[$component];
                }
            }

            // Eliminar duplicados consecutivos (puede ocurrir con nombres similares)
            $uniqueParts = [];
            $lastPart = null;

            foreach ($locationParts as $part) {
                if ($part !== $lastPart) {
                    $uniqueParts[] = $part;
                    $lastPart = $part;
                }
            }

            return !empty($uniqueParts)
                ? implode(', ', $uniqueParts)
                : 'Ubicación cercana a coordenadas: ' . round($lat, 4) . ', ' . round($lon, 4);

        } catch (\Exception $e) {
            return 'Error al obtener la ubicación: ' . $e->getMessage();
        }
    }
}