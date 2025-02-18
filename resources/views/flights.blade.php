<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Vuelos</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/flights.css') }}">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Resultados de Vuelos</h1>
        <a href="/" class="btn btn-secondary mb-4">Nueva búsqueda</a>

        <div class="date-navigation">
            <a href="{{ route('flights', [
                'departure' => request('departure'),
                'arrival' => request('arrival'),
                'date' => \Carbon\Carbon::parse(request('date'))->subDay()->format('Y-m-d'),
                'trip_type' => request('trip_type'),
                'adults' => request('adults'),
                'children' => request('children'),
                'infants_in_seat' => request('infants_in_seat'),
                'infants_on_lap' => request('infants_on_lap'),
                'travel_class' => request('travel_class'),
                'stops' => request('stops'),
            ]) }}"
                class="btn btn-primary {{ \Carbon\Carbon::parse(request('date'))->isToday() ? 'disabled' : '' }}"
                id="prevDayBtnNav">← Ayer</a>

            <div class="date-box" id="dateBoxYesterday">
                {{ \Carbon\Carbon::parse(request('date'))->subDay()->format('d/m') }}
            </div>
            <div class="date-box active" id="dateBoxToday">
                {{ \Carbon\Carbon::parse(request('date'))->format('d/m') }}
            </div>
            <div class="date-box" id="dateBoxTomorrow">
                {{ \Carbon\Carbon::parse(request('date'))->addDay()->format('d/m') }}
            </div>

            <a href="{{ route('flights', [
                'departure' => request('departure'),
                'arrival' => request('arrival'),
                'date' => \Carbon\Carbon::parse(request('date'))->addDay()->format('Y-m-d'),
                'trip_type' => request('trip_type'),
                'adults' => request('adults'),
                'children' => request('children'),
                'infants_in_seat' => request('infants_in_seat'),
                'infants_on_lap' => request('infants_on_lap'),
                'travel_class' => request('travel_class'),
                'stops' => request('stops'),
            ]) }}"
                class="btn btn-primary" id="nextDayBtnNav">Mañana →</a>
        </div>

        <div class="text-center mt-3">
            <strong>Origen:</strong> {{ request('departure') }} |
            <strong>Destino:</strong> {{ request('arrival') }}
        </div>

        <div class="card shadow p-4 mt-4">
            <h3 class="text-center">Información de Precios</h3>
            <p>
                <strong>Nivel de precio:</strong> <span id="priceLevel"></span>
                <strong>Rango típico de precios:</strong> <span id="priceRange"></span> €
            </p>
        </div>

        @php
            $categorias = [
                'Opciones destacadas' => $flights ?? [],
                'Otros vuelos' => $other_flights ?? [],
            ];

            if (empty($flights)) {
                $categorias = ['Vuelos' => $other_flights ?? []];
            }

        @endphp

        @php
            $noHayVuelos = empty($flights) && empty($other_flights);
        @endphp

        @if ($noHayVuelos)
            <div class="alert alert-danger text-center mt-4">No se encontraron vuelos disponibles.</div>
        @else
            @foreach ($categorias as $titulo => $listaVuelos)
                @if (!empty($listaVuelos))
                    <h2 class="mt-4">{{ $titulo }}</h2>
                    <div class="row" id="flightList">
                        @foreach ($listaVuelos as $flight)
                            @if (isset($flight['flights'][0]))
                                @php
                                    $detalleVuelo = $flight['flights'][0];
                                    $horaSalida = date('H:i', strtotime($detalleVuelo['departure_airport']['time']));
                                    $horaLlegada = date('H:i', strtotime($detalleVuelo['arrival_airport']['time']));
                                @endphp
                                <div class="col-md-6">
                                    <a href="{{ route('flight.show', ['id' => $detalleVuelo['flight_number']]) }}"
                                        class="text-decoration-none">
                                        <div class="card mb-4 shadow-sm clickable-card">
                                            <div class="card-body">
                                                <div class="flight-header">
                                                    <h5 class="card-title">
                                                        <img src="{{ $detalleVuelo['airline_logo'] }}" alt="Logo"
                                                            width="30">
                                                        {{ $detalleVuelo['airline'] }}
                                                    </h5>
                                                    <div class="flight-times">
                                                        {{ $horaSalida }} <i class="fa-solid fa-arrow-right"></i> {{ $horaLlegada }}
                                                    </div>
                                                </div>
                                                <p class="card-text">
                                                    <strong>Precio:</strong> €{{ $flight['price'] }} <br>
                                                    <strong>Duración total:</strong>
                                                    {{ intdiv($flight['total_duration'], 60) }}h
                                                    {{ $flight['total_duration'] % 60 }}min
                                                    <br>
                                                    <strong>Escalas:</strong>
                                                    @if (!empty($flight['layovers']))
                                                        @foreach ($flight['layovers'] as $layover)
                                                            {{ $layover['name'] }} ({{ $layover['duration'] }} min)
                                                        @endforeach
                                                    @else
                                                        Directo (sin escalas)
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
        <div class="card shadow p-4 mt-3">
            <h3 class="text-center">Evolución de Precios</h3>

            <div class="text-center price-chart-date" id="selectedDate">Fecha seleccionada:
                {{ \Carbon\Carbon::parse(request('date'))->format('d/m/y') }}</div>

            <canvas id="priceChart"></canvas>
        </div>

    </div>
    <script>
        window.flightPrices = {
            priceData: @json($prices['price_history'] ?? []),
            priceLevel: @json($prices['price_level'] ?? 'No disponible'),
            priceRange: @json($prices['typical_price_range'] ?? [])
        };
    </script>
    <script src="{{ asset('js/flight.js') }}"></script>

</body>

</html>
