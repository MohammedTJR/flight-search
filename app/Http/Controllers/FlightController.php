<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\FavoriteFlight;
use App\Services\ApiKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\ImageService;

class FlightController extends Controller
{
    protected $apiKeyService;

    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }

    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $departure = $request->input('departure');
        $arrival = $request->input('arrival');
        $date = $request->input('date');
        $returnDate = $request->input('return_date');
        $adults = $request->input('adults', 1);
        $children = $request->input('children', 0);
        $infants_in_seat = $request->input('infants_in_seat', 0);
        $infants_on_lap = $request->input('infants_on_lap', 0);
        $travel_class = $request->input('travel_class', 'economy');
        $stops = $request->input('stops', 0);

        // Obtener una API key válida usando el servicio
        $apiKey = $this->apiKeyService->getValidApiKey();

        if (!$apiKey) {
            return view('flights', [
                'flights' => [],
                'other_flights' => [],
                'prices' => [],
                'error' => 'No hay API keys disponibles en este momento. Inténtalo más tarde.'
            ]);
        }

        // Construcción de la URL para consultar la API de SerpApi (Google Flights)
        $url = "https://serpapi.com/search.json?"
            . "engine=google_flights"
            . "&departure_id={$departure}"
            . "&arrival_id={$arrival}"
            . "&outbound_date={$date}"
            . "&type=2"
            . "&currency=EUR"
            . "&hl=es"
            . "&adults={$adults}"
            . "&children={$children}"
            . "&infants_in_seat={$infants_in_seat}"
            . "&infants_on_lap={$infants_on_lap}"
            . "&travel_class={$travel_class}"
            . "&stops={$stops}"
            . "&api_key={$apiKey}";

        // Guardar parámetros de búsqueda en la sesión para usarlos después
        session()->put('search_params', [
            'adults' => $adults,
            'children' => $children,
            'infants_in_seat' => $infants_in_seat,
            'infants_on_lap' => $infants_on_lap,
            'travel_class' => $travel_class
        ]);

        try {
            // Realiza la solicitud GET a la API
            $response = Http::get($url);

            // Verifica si la respuesta es exitosa
            if ($response->successful()) {
                $data = $response->json() ?? [];

                // Verifica si hay un error relacionado con la API key
                if (
                    isset($data['error']) && (
                        strpos($data['error'], 'api_key') !== false ||
                        strpos($data['error'], 'limit') !== false ||
                        strpos($data['error'], 'quota') !== false
                    )
                ) {
                    // Si hay un error relacionado con la API key, márcarla como inválida
                    $this->apiKeyService->markKeyAsInvalid($apiKey);

                    // Intentar nuevamente con una nueva API key
                    return $this->search($request);
                }

                $flights = $data['best_flights'] ?? [];
                $other_flights = $data['other_flights'] ?? [];
                $prices = $data['price_insights'] ?? [];

                // Almacena los vuelos en la sesión
                session(['flights' => array_merge($flights, $other_flights)]);

                return view('flights', compact('flights', 'other_flights', 'prices'));
            } else {
                // Si la respuesta no es exitosa, intentar con otra API key
                $this->apiKeyService->markKeyAsInvalid($apiKey);

                // Verificar si es un error de límite de API
                if ($response->status() == 429) {
                    Log::warning("API key agotada: $apiKey. Intentando con otra clave.");
                    return $this->search($request);
                }

                // Otro tipo de error
                Log::error("Error en la búsqueda de vuelos: " . $response->body());
                return view('flights', [
                    'flights' => [],
                    'other_flights' => [],
                    'prices' => [],
                    'error' => 'Error al buscar vuelos. Inténtalo de nuevo.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Excepción al buscar vuelos: " . $e->getMessage());
            return view('flights', [
                'flights' => [],
                'other_flights' => [],
                'prices' => [],
                'error' => 'Error al conectar con el servicio de búsqueda de vuelos.'
            ]);
        }
    }

    public function show($id)
    {
        $flights = session('flights', []);
        $selectedFlight = collect($flights)->firstWhere('flights.0.flight_number', $id);

        if (!$selectedFlight) {
            abort(404, 'Vuelo no encontrado');
        }

        $bookingOptions = [];
        if (!empty($selectedFlight['booking_token'])) {
            $bookingOptions = $this->getBookingOptions(
                $selectedFlight['booking_token'],
                $selectedFlight // Pasamos todos los datos del vuelo
            );
        }

        return view('details', [
            'flight' => $selectedFlight,
            'bookingOptions' => $bookingOptions
        ]);
    }

    public function addFavorite(Request $request)
    {
        $request->validate([
            'flight_id' => 'required',
            'origin' => 'required|size:3',
            'destination' => 'required|size:3',
            'departure_date' => 'required|date',
            'price' => 'required|numeric',
            'airline' => 'required'
        ]);

        $existing = FavoriteFlight::where('user_id', auth()->id())
            ->where('flight_id', $request->flight_id)
            ->where('airline', $request->airline)
            ->where('departure_date', $request->departure_date)
            ->first();

        if ($existing) {
            return response()->json([
                'error' => 'Ya tienes este vuelo en favoritos',
                'id' => $existing->id,
                'is_favorite' => true
            ], 409);
        }

        $favorite = FavoriteFlight::create([
            'user_id' => auth()->id(),
            'flight_id' => $request->flight_id,
            'origin' => $request->origin,
            'destination' => $request->destination,
            'departure_date' => $request->departure_date,
            'price' => $request->price,
            'airline' => $request->airline
        ]);

        return response()->json([
            'id' => $favorite->id,
            'message' => 'Vuelo guardado en favoritos',
            'is_favorite' => true
        ]);
    }

    public function removeFavorite(FavoriteFlight $favoriteFlight)
    {
        if ($favoriteFlight->user_id !== auth()->id()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
            abort(403);
        }

        $favoriteFlight->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Vuelo eliminado de favoritos',
                'is_favorite' => false
            ]);
        }

        return redirect()->route('favorites.show')->with('success', 'Vuelo eliminado de favoritos');
    }

    public function showFavorites()
    {
        $favorites = auth()->user()->favoriteFlights()->latest()->get();

        $imageService = app(ImageService::class);

        foreach ($favorites as $favorite) {
            $airport = Airport::where('iata', $favorite->destination)->first();

            $cityName = $airport ? $airport->city : $favorite->destination;

            $favorite->destination_image = $imageService->getCityImage($cityName);
        }

        return view('favorites.index', ['favorites' => $favorites]);
    }

    public function showFavoriteDetails(FavoriteFlight $favoriteFlight)
    {
        if ($favoriteFlight->user_id !== auth()->id()) {
            abort(403, 'No estás autorizado para ver este vuelo');
        }

        $apiKey = $this->apiKeyService->getValidApiKey();

        if (!$apiKey) {
            return redirect()->route('favorites.show')
                ->with('error', 'No hay API keys disponibles en este momento. Inténtalo más tarde.');
        }

        $departure = $favoriteFlight->origin;
        $arrival = $favoriteFlight->destination;
        $date = $favoriteFlight->departure_date->format('Y-m-d');

        $searchParams = session()->get('search_params', []);
        $adults = $searchParams['adults'] ?? 1;
        $children = $searchParams['children'] ?? 0;
        $infants_in_seat = $searchParams['infants_in_seat'] ?? 0;
        $infants_on_lap = $searchParams['infants_on_lap'] ?? 0;
        $travel_class = $searchParams['travel_class'] ?? 'economy';
        $stops = 0;

        $url = "https://serpapi.com/search.json?"
            . "engine=google_flights"
            . "&departure_id={$departure}"
            . "&arrival_id={$arrival}"
            . "&outbound_date={$date}"
            . "&type=2"
            . "&currency=EUR"
            . "&hl=es"
            . "&adults={$adults}"
            . "&children={$children}"
            . "&infants_in_seat={$infants_in_seat}"
            . "&infants_on_lap={$infants_on_lap}"
            . "&travel_class={$travel_class}"
            . "&stops={$stops}"
            . "&api_key={$apiKey}";

        try {
            // Realizar la consulta a la API
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json() ?? [];

                // Verificar si hay errores de API key
                if (
                    isset($data['error']) && (
                        strpos($data['error'], 'api_key') !== false ||
                        strpos($data['error'], 'limit') !== false ||
                        strpos($data['error'], 'quota') !== false
                    )
                ) {
                    $this->apiKeyService->markKeyAsInvalid($apiKey);
                    return $this->showFavoriteDetails($favoriteFlight); // Reintentar con otra clave
                }

                // Combinar todos los vuelos
                $allFlights = array_merge($data['best_flights'] ?? [], $data['other_flights'] ?? []);

                // Buscar el vuelo específico por número de vuelo y aerolínea
                $flight = null;
                foreach ($allFlights as $f) {
                    if (
                        isset($f['flights'][0]['flight_number']) &&
                        $f['flights'][0]['flight_number'] == $favoriteFlight->flight_id &&
                        $f['flights'][0]['airline'] == $favoriteFlight->airline
                    ) {
                        $flight = $f;
                        break;
                    }
                }

                // Si no se encuentra el vuelo específico, usar el primero disponible
                if (!$flight && !empty($allFlights)) {
                    $flight = $allFlights[0];
                    Log::info("No se encontró el vuelo exacto. Usando el primero disponible.");
                } elseif (!$flight) {
                    return redirect()->route('favorites.show')
                        ->with('error', 'No se encontraron vuelos disponibles para esta ruta y fecha.');
                }

                // Almacenar el vuelo en la sesión para referencia futura
                session(['flights' => $allFlights]);

                // Verificar si el precio ha cambiado y actualizar en la base de datos
                if (isset($flight['price']) && $flight['price'] != $favoriteFlight->price) {
                    $oldPrice = $favoriteFlight->price;
                    $favoriteFlight->price = $flight['price'];
                    $favoriteFlight->save();

                    // Enviar mensaje de cambio de precio a la vista
                    session()->flash('price_changed', [
                        'old_price' => $oldPrice,
                        'new_price' => $flight['price']
                    ]);
                }

                $bookingOptions = [];
                $bookingToken = $flight['booking_token'] ?? null;

                if ($bookingToken) {
                    $bookingOptions = $this->getBookingOptions($bookingToken);
                }

                return view('details', [
                    'flight' => $flight,
                    'bookingOptions' => $bookingOptions
                ]);

            } else {
                // Si hay error de la API, intentar con otra clave
                $this->apiKeyService->markKeyAsInvalid($apiKey);

                if ($response->status() == 429) {
                    Log::warning("API key agotada: $apiKey. Intentando con otra clave.");
                    return $this->showFavoriteDetails($favoriteFlight);
                }

                Log::error("Error en la búsqueda del vuelo favorito: " . $response->body());
                return redirect()->route('favorites.show')
                    ->with('error', 'Error al recuperar la información del vuelo. Inténtelo de nuevo más tarde.');
            }
        } catch (\Exception $e) {
            Log::error("Excepción al buscar vuelo favorito: " . $e->getMessage());
            return redirect()->route('favorites.show')
                ->with('error', 'Error al conectar con el servicio de búsqueda de vuelos.');
        }
    }

    private function getBookingOptions($bookingToken, $flightData)
    {
        $apiKey = $this->apiKeyService->getValidApiKey();
        if (!$apiKey) {
            return null;
        }

        // Parámetros OBLIGATORIOS para la API
        $query = [
            'engine' => 'google_flights',
            'type' => '2', // ¡IMPORTANTE! Siempre type=2
            'departure_id' => $flightData['flights'][0]['departure_airport']['id'] ?? '',
            'arrival_id' => $flightData['flights'][0]['arrival_airport']['id'] ?? '',
            'outbound_date' => date('Y-m-d', strtotime($flightData['flights'][0]['departure_airport']['time'] ?? '')),
            'booking_token' => $bookingToken,
            'currency' => 'EUR',
            'hl' => 'es',
            'api_key' => $apiKey
        ];

        try {
            $response = Http::get('https://serpapi.com/search.json', $query);

            if ($response->successful()) {
                $data = $response->json();

                // Manejo de errores de API key
                if (isset($data['error'])) {
                    if (
                        strpos($data['error'], 'api_key') !== false ||
                        strpos($data['error'], 'limit') !== false ||
                        strpos($data['error'], 'quota') !== false
                    ) {
                        $this->apiKeyService->markKeyAsInvalid($apiKey);
                        return $this->getBookingOptions($bookingToken, $flightData); // Reintento
                    }
                }

                return $data['booking_options'] ?? [];
            } else {
                if ($response->status() == 429) {
                    $this->apiKeyService->markKeyAsInvalid($apiKey);
                    return $this->getBookingOptions($bookingToken, $flightData);
                }
                Log::error("Error en booking options: " . $response->body());
                return [];
            }
        } catch (\Exception $e) {
            Log::error("Excepción en booking options: " . $e->getMessage());
            return [];
        }
    }
}