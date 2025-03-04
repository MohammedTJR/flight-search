<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlightController extends Controller
{
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

        $apiKey = env('SERPAPI_KEY');

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
        // Realiza la solicitud GET a la API

        $response = Http::get($url);
        // Verifica si la respuesta es exitosa

        if ($response->successful()) {
            $data = $response->json() ?? [];
            $flights = $data['best_flights'] ?? [];
            $other_flights = $data['other_flights'] ?? [];
            $prices = $data['price_insights'] ?? [];
            // Almacena los vuelos en la sesión para poder acceder a ellos en otras partes de la aplicación

            session(['flights' => array_merge($flights, $other_flights)]);
        } else {
            // En caso de error en la solicitud, inicializa las variables vacías

            $flights = [];
            $other_flights = [];
            $prices = [];
        }
        // Retorna la vista 'flights' con los datos obtenidos

        return view('flights', compact('flights', 'other_flights', 'prices'));
    }

    public function show($id)
    {
        $flights = session('flights', []);

        $selectedFlight = collect($flights)->firstWhere('flights.0.flight_number', $id);

        if (!$selectedFlight) {
            abort(404, 'Vuelo no encontrado');
        }

        return view('details', ['flight' => $selectedFlight]);
    }
}
