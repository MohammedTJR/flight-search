@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/flights.css') }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('titulo_pagina', 'Resultados de Vuelos')

@section('contenido')
    <div class="container mt-5">
        <h1 class="text-center mb-5">Resultados de Vuelos</h1>
        <!-- Contenedor principal para la navegación de fechas -->

        <div class="date-navigation d-flex justify-content-center align-items-center gap-3">
            <!-- Enlace para navegar al día anterior -->

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
    ]) }}" class="btn btn-primary {{ \Carbon\Carbon::parse(request('date'))->isToday() ? 'disabled' : '' }}"
                id="prevDayBtnNav">
                <i class="fas fa-arrow-left"></i>
            </a>
            <!-- Caja que muestra la fecha del día anterior -->

            <div class="date-box" id="dateBoxYesterday">
                {{ \Carbon\Carbon::parse(request('date'))->subDay()->format('d/m') }}
            </div>
            <!-- Caja que muestra la fecha actual (activa) -->
            <div class="date-box active" id="dateBoxToday">
                {{ \Carbon\Carbon::parse(request('date'))->format('d/m') }}
            </div>
            <!-- Caja que muestra la fecha del día siguiente -->
            <div class="date-box" id="dateBoxTomorrow">
                {{ \Carbon\Carbon::parse(request('date'))->addDay()->format('d/m') }}
            </div>
            <!-- Enlace para navegar al día siguiente -->
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
    ]) }}" class="btn btn-primary" id="nextDayBtnNav">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="text-center mt-3">
            @if(request('departure'))
                <strong>Origen:</strong> {{ request('departure') }} |
            @endif
            @if(request('arrival'))
                <strong>Destino:</strong> {{ request('arrival') }}
            @endif
        </div>

        <div class="card shadow p-4 mt-4">
            <h3 class="text-center">Información de Precios</h3>
            <p>
                @if(isset($prices['price_level']))
                    <strong>Nivel de precio:</strong> <span id="priceLevel">{{ $prices['price_level'] }}</span>
                @endif
                @if(isset($prices['typical_price_range']))
                    <strong>Rango típico de precios:</strong> <span
                        id="priceRange">{{ implode(' - ', $prices['typical_price_range']) }}</span> €
                @endif
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
                    <!-- Contenedor principal para la lista de vuelos -->

                    <div class="row" id="flightList">
                        <!-- Itera sobre cada vuelo en la lista de vuelos ($listaVuelos) -->

                        @foreach ($listaVuelos as $flight)
                            <!-- Verifica si el vuelo tiene al menos un segmento de vuelo (flights[0] existe) -->
                            @if (isset($flight['flights'][0]))
                                @php
                                    // Extrae los detalles del primer segmento para la salida
                                    $primerSegmento = $flight['flights'][0];
                                    // Obtiene el último segmento para la llegada real
                                    $ultimoSegmento = end($flight['flights']);

                                    // Formatea la hora de salida del primer segmento
                                    $horaSalida = isset($primerSegmento['departure_airport']['time'])
                                        ? date('H:i', strtotime($primerSegmento['departure_airport']['time']))
                                        : 'No disponible';

                                    // Formatea la hora de llegada del último segmento
                                    $horaLlegada = isset($ultimoSegmento['arrival_airport']['time'])
                                        ? date('H:i', strtotime($ultimoSegmento['arrival_airport']['time']))
                                        : 'No disponible';

                                    // Calcular la diferencia de días
                                    $fechaSalida = isset($primerSegmento['departure_airport']['time'])
                                        ? \Carbon\Carbon::parse($primerSegmento['departure_airport']['time'])
                                        : null;
                                    $fechaLlegada = isset($ultimoSegmento['arrival_airport']['time'])
                                        ? \Carbon\Carbon::parse($ultimoSegmento['arrival_airport']['time'])
                                        : null;

                                    $diasDiferencia = null;
                                    if ($fechaSalida && $fechaLlegada) {
                                        // Simplemente comparamos los días del mes
                                        $diaSalida = $fechaSalida->day;
                                        $diaLlegada = $fechaLlegada->day;

                                        // Si el día de llegada es menor que el día de salida, significa que hemos cambiado de mes
                                        if ($diaLlegada < $diaSalida) {
                                            $diasDiferencia = 1; // llegada al día siguiente
                                        } else {
                                            $diasDiferencia = $diaLlegada - $diaSalida;
                                        }
                                    }
                                @endphp
                                <div class="col-md-6">
                                    <!-- Enlace que redirige a la página de detalles del vuelo -->
                                    <a href="{{ isset($primerSegmento['flight_number']) ? route('flight.show', ['id' => $primerSegmento['flight_number']]) : '#' }}"
                                        class="text-decoration-none">
                                        <div class="card mb-4 shadow-sm clickable-card">
                                            <div class="card-body">
                                                <div class="flight-header">
                                                    <h5 class="card-title">
                                                        @if(isset($primerSegmento['airline_logo']))
                                                            <img src="{{ $primerSegmento['airline_logo'] }}" alt="Logo" width="30">
                                                        @endif
                                                        @if(isset($primerSegmento['airline']))
                                                            {{ $primerSegmento['airline'] }}
                                                        @else
                                                            Aerolínea no disponible
                                                        @endif
                                                    </h5>
                                                    <div class="flight-times">
                                                        {{ $horaSalida }} <i class="fa-solid fa-arrow-right"></i> {{ $horaLlegada }}
                                                        @if($diasDiferencia === 1)
                                                            <span class="text-info ms-2" title="Llega al día siguiente">
                                                                <i class="fas fa-moon"></i> +1
                                                            </span>
                                                        @elseif($diasDiferencia === 2)
                                                            <span class="text-info ms-2" title="Llega en dos días">
                                                                <i class="fas fa-moon"></i> +2
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="card-text">
                                                    <strong>Precio:</strong>
                                                    @if (!isset($flight['price']))
                                                        <span class="text-danger">No disponible</span>
                                                    @else
                                                        €{{ $flight['price'] }}
                                                    @endif
                                                    <br>
                                                    <strong>Duración total:</strong>
                                                    @if(isset($flight['total_duration']))
                                                        {{ intdiv($flight['total_duration'], 60) }}h
                                                        {{ $flight['total_duration'] % 60 }}min
                                                    @else
                                                        No disponible
                                                    @endif
                                                    <br>
                                                    <strong>Escalas:</strong>
                                                    @if (!empty($flight['layovers']))
                                                        @foreach ($flight['layovers'] as $layover)
                                                            @if(isset($layover['name']) && isset($layover['duration']))
                                                                {{ $layover['name'] }} ({{ $layover['duration'] }} min)
                                                            @endif
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

        @if(isset($prices['price_history']))
            <div class="card shadow p-4 mt-3 mb-4">
                <h3 class="text-center">Evolución de Precios</h3>
                <div class="text-center price-chart-date" id="selectedDate">Fecha seleccionada:
                    {{ \Carbon\Carbon::parse(request('date'))->format('d/m/y') }}
                </div>
                <canvas id="priceChart"></canvas>
            </div>
        @endif
    </div>

    @section('scripts')
        <script>
            window.flightPrices = {
                priceData: @json($prices['price_history'] ?? []),
                priceLevel: @json($prices['price_level'] ?? 'No disponible'),
                priceRange: @json($prices['typical_price_range'] ?? [])
            };
        </script>
        <script src="{{ asset('js/flight.js') }}"></script>
    @endsection

@endsection