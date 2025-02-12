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
        $tripType = $request->input('trip_type', '1'); // 1: Ida y vuelta (default), 2: Solo ida
        $adults = $request->input('adults', 1);
        $children = $request->input('children', 0);
        $infants_in_seat = $request->input('infants_in_seat', 0);
        $infants_on_lap = $request->input('infants_on_lap', 0);
        $travel_class = $request->input('travel_class', 'economy');
        $stops = $request->input('stops', 0);

        $apiKey = env('SERPAPI_KEY');

        $url = "https://serpapi.com/search.json?"
            . "engine=google_flights"
            . "&departure_id={$departure}"
            . "&arrival_id={$arrival}"
            . "&outbound_date={$date}"
            . "&type={$tripType}"
            . "&currency=EUR"
            . "&hl=en"
            . "&adults={$adults}"
            . "&children={$children}"
            . "&infants_in_seat={$infants_in_seat}"
            . "&infants_on_lap={$infants_on_lap}"
            . "&travel_class={$travel_class}"
            . "&stops={$stops}"
            . "&api_key={$apiKey}";

        // Solo agregar return_date si el viaje es ida y vuelta
        if ($tripType == "1" && !empty($returnDate)) {
            $url .= "&return_date={$returnDate}";
        }

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json() ?? [];
            $flights = $data['best_flights'] ?? [];
            $other_flights = $data['other_flights'] ?? [];
            $prices = $data['price_insights'] ?? [];
            session(['flights' => array_merge($flights, $other_flights)]);
        } else {
            $flights = [];
            $other_flights = [];
            $prices = [];
        }


        return view('flights', compact('flights', 'other_flights', 'prices'));
    }

    public function show($id)
    {
        // Aquí deberías recuperar la información del vuelo desde la API o base de datos.
        // Por ahora, simularemos una respuesta con datos de prueba.

        $flights = session('flights', []); // Recuperar la lista de vuelos de la sesión

        // Buscar el vuelo por su ID en la lista
        $selectedFlight = collect($flights)->firstWhere('flights.0.flight_number', $id);

        if (!$selectedFlight) {
            abort(404, 'Vuelo no encontrado');
        }

        return view('details', ['flight' => $selectedFlight]);
    }
}
