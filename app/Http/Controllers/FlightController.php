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
        $passengers = $request->input('passengers');

        $apiKey = env('SERPAPI_KEY');

        $url = "https://serpapi.com/search.json?"
            . "engine=google_flights"
            . "&departure_id={$departure}"
            . "&arrival_id={$arrival}"
            . "&outbound_date={$date}"
            . "&type={$tripType}"
            . "&currency=EUR"
            . "&hl=en"
            . "&adults={$passengers}"
            . "&api_key={$apiKey}";

        // Solo agregar return_date si el viaje es ida y vuelta
        if ($tripType == "1" && !empty($returnDate)) {
            $url .= "&return_date={$returnDate}";
        }

        $response = Http::get($url);
        $data = $response->json();

        // Si la API devuelve datos, los tomamos; si no, inicializamos un array vacío
        $flights = $data['best_flights'] ?? [];

        return view('flights', compact('flights'));
    }
}
