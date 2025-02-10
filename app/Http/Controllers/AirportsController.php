<?php

namespace App\Http\Controllers;

use App\Models\Airport;

class AirportsController extends Controller
{
    public function airports()
    {
        $airports = Airport::orderBy('country')
            ->get()
            ->groupBy(fn($airport) => "{$airport->city} ({$airport->country})");

        return response()->json($airports);
    }
}
