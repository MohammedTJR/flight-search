<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\TrackedFlight;
use App\Services\FlightTrackerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

    /**
     * Lugar donde está el avión, en texto. Se cachea por zona (coordenadas a 2 decimales,
     * ~1 km) para no consultar LocationIQ en cada visita a la página.
     */
    private function getAddressFromCoordinates($lat, $lon)
    {
        if (!is_numeric($lat) || !is_numeric($lon)) {
            return 'Coordenadas inválidas';
        }

        $key = 'tracker_place_' . round($lat, 2) . '_' . round($lon, 2);

        if ($cached = Cache::get($key)) {
            return $cached;
        }

        [$place, $definitive] = $this->lookupPlace($lat, $lon);

        $text = $place ?? $this->describeCoordinates($lat, $lon);

        // Solo se recuerda si la respuesta es firme (hay lugar, o LocationIQ confirma que no lo hay);
        // un fallo puntual de red no debe quedarse guardado horas
        if ($definitive) {
            Cache::put($key, $text, 6 * 3600);
        }

        return $text;
    }

    /**
     * @return array{0: ?string, 1: bool} [lugar o null, si la respuesta es definitiva]
     */
    private function lookupPlace($lat, $lon): array
    {
        try {
            $apiKey = 'pk.88b33c8a31452200a7ffd22cb8ddb2c2'; // Reemplaza con tu API key de LocationIQ

            $response = Http::timeout(8)->retry(2, 200, throw: false)->get('https://us1.locationiq.com/v1/reverse.php', [
                'key' => $apiKey,
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
                'accept-language' => 'es',
                'zoom' => 10,
                'addressdetails' => 1
            ]);

            // 404 «Unable to geocode»: no hay lugar (mar abierto, zona sin población)
            if ($response->status() === 404) {
                return [null, true];
            }

            if (!$response->successful()) {
                Log::warning('LocationIQ respondió ' . $response->status());
                return [null, false];
            }

            $address = $response->json('address') ?? [];

            // Orden de prioridad para los componentes de la dirección
            $parts = [];
            foreach (['village', 'town', 'city', 'municipality', 'county', 'state', 'country'] as $component) {
                if (!empty($address[$component]) && end($parts) !== $address[$component]) {
                    $parts[] = $address[$component];
                }
            }

            if ($parts) {
                return [implode(', ', $parts), true];
            }

            return [$response->json('display_name'), true];
        } catch (\Exception $e) {
            Log::warning('No se pudo consultar LocationIQ: ' . $e->getMessage());
            return [null, false];
        }
    }

    private function describeCoordinates($lat, $lon): string
    {
        return sprintf(
            'Sobre el mar o una zona sin población · %.2f° %s, %.2f° %s',
            abs($lat),
            $lat >= 0 ? 'N' : 'S',
            abs($lon),
            $lon >= 0 ? 'E' : 'O'
        );
    }
}
