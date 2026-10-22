<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\TrackedFlight;
use App\Services\FlightTrackerService;
use Illuminate\Support\Facades\Auth;
use Log;

class FlightTrackingController extends Controller
{
    public function __construct(private FlightTrackerService $tracker)
    {
    }

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

        $result = $this->tracker->lookup($flightNumber);

        if ($result === null) {
            return back()->with('error', 'No se encontró información para el vuelo ' . $flightNumber);
        }

        // Guardar el vuelo seguido
        Auth::user()->trackedFlights()->create([
            'flight_number' => $flightNumber,
            'flight_data' => $result['flight_data'],
            'position_data' => $result['position_data'],
            'last_checked_at' => now()
        ]);

        $mensaje = $result['live']
            ? 'Vuelo añadido: ahora mismo está en el aire.'
            : 'Vuelo añadido. Ahora no tiene señal ADS-B (aún no ha despegado o ya aterrizó): pulsa «Actualizar» cuando esté volando.';

        return redirect()->route('flight.tracking.index')->with('success', $mensaje);
    }

    public function refreshFlight($id)
    {
        $trackedFlight = Auth::user()->trackedFlights()->findOrFail($id);

        // Se pasan los datos guardados para reutilizar la matrícula que operó el vuelo la última vez
        $result = $this->tracker->lookup($trackedFlight->flight_number, $trackedFlight->flight_data ?? []);

        if ($result === null) {
            return back()->with('error', 'No se pudo consultar el vuelo ahora mismo. Inténtalo de nuevo en un momento.');
        }

        $trackedFlight->update([
            'flight_data' => $result['flight_data'],
            // Sin señal ahora, se conserva la última posición conocida
            'position_data' => $result['position_data'] ?? $trackedFlight->position_data,
            'last_checked_at' => now()
        ]);

        return back()->with('success', $result['live']
            ? 'Información del vuelo actualizada.'
            : 'Consultado: ahora no hay señal ADS-B de este vuelo. Se mantiene la última posición conocida.');
    }

    public function destroy($id)
    {
        $trackedFlight = Auth::user()->trackedFlights()->findOrFail($id);
        $trackedFlight->delete();

        return back()->with('success', 'Vuelo eliminado de tu lista de seguimiento.');
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