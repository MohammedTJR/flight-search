<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'index']);
Route::get('/flights', [FlightController::class, 'search']);

Route::get('/airports', function () {
    $file = public_path('airports.dat');

    if (!file_exists($file)) {
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    }

    $airports = [];
    $handle = fopen($file, 'r');

    while (($data = fgetcsv($handle)) !== FALSE) {
        if (!empty($data[4])) { // Solo incluir aeropuertos con código IATA
            $city_country = "{$data[2]} ({$data[3]})"; // "Ciudad (País)"
            $airports[$city_country][] = [
                'name' => $data[1],  // Nombre del aeropuerto
                'iata' => $data[4]   // Código IATA
            ];
        }
    }

    fclose($handle);
    
    return response()->json($airports);
});

