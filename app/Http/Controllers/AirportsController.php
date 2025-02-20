<?php

namespace App\Http\Controllers;

use App\Models\Airport;

class AirportsController extends Controller
{
    public function airports()
    {
        // Obtiene los aeropuertos que tienen un código IATA y los ordena por país
        $airports = Airport::whereNotNull('iata')
            ->orderBy('country')
            ->get()
            // Agrupa los aeropuertos por ciudad y país en el formato "Ciudad (País)"
            ->groupBy(fn($airport) => "{$airport->city} ({$airport->country})");

        // Retorna la respuesta en formato JSON
        return response()->json($airports);
    }
}
