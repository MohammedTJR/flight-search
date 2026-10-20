@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/flights.css') }}?v={{ filemtime(public_path('css/flights.css')) }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('titulo_pagina', 'Resultados de Vuelos')

@section('contenido')
    @php
        $categorias = [
            'Opciones destacadas' => $flights ?? [],
            'Otros vuelos' => $other_flights ?? [],
        ];

        if (empty($flights)) {
            $categorias = ['Vuelos' => $other_flights ?? []];
        }

        $noHayVuelos = empty($flights) && empty($other_flights);

        $fmtDuracion = function ($minutos) {
            return intdiv((int) $minutos, 60) . 'h ' . str_pad((int) $minutos % 60, 2, '0', STR_PAD_LEFT) . 'min';
        };

        // Parámetros de búsqueda que se conservan al cambiar de fecha
        $params = request()->only([
            'departure', 'arrival', 'trip_type', 'adults', 'children',
            'infants_in_seat', 'infants_on_lap', 'travel_class', 'stops',
        ]);
        $fechaBase = \Carbon\Carbon::parse(request('date'));

        // Fechas en español sin los puntos de las abreviaturas ("mié 21 oct")
        $fmtFecha = fn($d, $formato) => str_replace('.', '', $d->copy()->locale('es')->translatedFormat($formato));

        $clases = [1 =>'Económica', 2 => 'Económica Premium', 3 => 'Business', 4 => 'Primera clase'];
        $claseTexto = $clases[(int) request('travel_class', 1)] ?? 'Económica';
        $pasajeros = (int) request('adults', 1) + (int) request('children', 0)
            + (int) request('infants_in_seat', 0) + (int) request('infants_on_lap', 0);

        // Vuelos con al menos un segmento, para estadísticas y filtros
        $todos = collect(array_merge($flights ?? [], $other_flights ?? []))
            ->filter(fn($f) => isset($f['flights'][0]))->values();
        $precioMin = $todos->filter(fn($f) => isset($f['price']))->min('price');
        $duracionMin = $todos->filter(fn($f) => isset($f['total_duration']))->min('total_duration');

        $masBarato = $todos->filter(fn($f) => isset($f['price']))->sortBy('price')->first();
        $masRapido = $todos->filter(fn($f) => isset($f['total_duration']))->sortBy('total_duration')->first();
        $salidaTemprana = $todos->filter(fn($f) => isset($f['flights'][0]['departure_airport']['time']))
            ->sortBy(fn($f) => $f['flights'][0]['departure_airport']['time'])->first();

        // Aerolíneas disponibles con su precio mínimo (para el filtro)
        $aerolineas = [];
        foreach ($todos as $f) {
            $nombre = $f['flights'][0]['airline'] ?? 'Aerolínea no disponible';
            $precio = $f['price'] ?? null;
            if (!isset($aerolineas[$nombre])) {
                $aerolineas[$nombre] = $precio;
            } elseif ($precio !== null && ($aerolineas[$nombre] === null || $precio < $aerolineas[$nombre])) {
                $aerolineas[$nombre] = $precio;
            }
        }
        ksort($aerolineas);

        $hayPrecios = isset($prices['price_level']) || isset($prices['typical_price_range'])
            || !empty($prices['price_history']);
    @endphp

    {{-- Resumen de la búsqueda y selector de fechas --}}
    <section class="fr-top">
        <div class="fr-wrap">
            <div class="fr-summary">
                <div class="fr-route">
                    <span class="fr-route-dot"></span>
                    <span>{{ request('departure') ?: '—' }}</span>
                    <i class="fas fa-plane"></i>
                    <span>{{ request('arrival') ?: '—' }}</span>
                </div>
                <span class="fr-summary-item"><i class="far fa-calendar"></i>{{ $fmtFecha($fechaBase, 'D, d M Y') }}</span>
                <span class="fr-summary-item"><i class="far fa-user"></i>{{ $pasajeros }} {{ $pasajeros === 1 ? 'pasajero' : 'pasajeros' }} · {{ $claseTexto }}</span>
                <a href="{{ route('home') }}" class="fr-modify"><i class="fas fa-sliders"></i>Modificar búsqueda</a>
            </div>

            <nav class="fr-dates" aria-label="Fecha de salida">
                @for ($i = -3; $i <= 3; $i++)
                    @php
                        $dia = $fechaBase->copy()->addDays($i);
                        $pasado = $dia->lt(\Carbon\Carbon::today());
                        $esActivo = $i === 0;
                    @endphp
                    @if ($pasado)
                        <span class="fr-date is-disabled">
                            <small>{{ $fmtFecha($dia, 'D, d M') }}</small>
                        </span>
                    @else
                        <a href="{{ route('flights', $params + ['date' => $dia->format('Y-m-d')]) }}"
                            class="fr-date {{ $esActivo ? 'is-active' : '' }}" @if ($esActivo) aria-current="date" @endif>
                            <small>{{ $fmtFecha($dia, 'D, d M') }}</small>
                        </a>
                    @endif
                @endfor
            </nav>
        </div>
    </section>

    <div class="fr-wrap fr-body">
        @if (isset($error))
            <div class="fr-alert fr-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ $error }}</div>
        @endif

        @if ($noHayVuelos)
            <div class="fr-empty">
                <i class="fas fa-plane-slash"></i>
                <h2>No se encontraron vuelos disponibles</h2>
                <p>Prueba con otra fecha o modifica la búsqueda.</p>
                <a href="{{ route('home') }}" class="fr-cta">Modificar búsqueda</a>
            </div>
        @else
            <div class="fr-layout">
                {{-- Filtros --}}
                <aside class="fr-filters">
                    <button class="fr-filters-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#filtersPanel"
                        aria-expanded="false" aria-controls="filtersPanel">
                        <span><i class="fas fa-filter"></i>Filtros</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="collapse fr-filters-panel" id="filtersPanel">
                        <div class="fr-filters-head">
                            <h2><i class="fas fa-filter"></i>Filtros</h2>
                            <button type="button" class="fr-reset" id="filtersReset">Restablecer</button>
                        </div>

                        <fieldset class="fr-group-filter">
                            <legend>Escalas</legend>
                            <label class="fr-check"><input type="checkbox" name="stops_filter" value="0" checked>
                                <span>Directo</span><em data-stops-info="0"></em></label>
                            <label class="fr-check"><input type="checkbox" name="stops_filter" value="1" checked>
                                <span>1 escala</span><em data-stops-info="1"></em></label>
                            <label class="fr-check"><input type="checkbox" name="stops_filter" value="2" checked>
                                <span>2+ escalas</span><em data-stops-info="2"></em></label>
                        </fieldset>

                        <fieldset class="fr-group-filter">
                            <legend>Aerolíneas</legend>
                            @foreach ($aerolineas as $nombre => $precio)
                                <label class="fr-check"><input type="checkbox" name="airline_filter" value="{{ $nombre }}" checked>
                                    <span>{{ $nombre }}</span>
                                    @if ($precio !== null)<em>{{ $precio }} €</em>@endif
                                </label>
                            @endforeach
                        </fieldset>

                        <fieldset class="fr-group-filter">
                            <legend>Hora de salida
                                <b id="hoursLabel">00:00 – 23:59</b>
                            </legend>
                            <div class="fr-range">
                                <label for="hourMin">Desde</label>
                                <input type="range" id="hourMin" min="0" max="23" step="1" value="0">
                            </div>
                            <div class="fr-range">
                                <label for="hourMax">Hasta</label>
                                <input type="range" id="hourMax" min="0" max="23" step="1" value="23">
                            </div>
                        </fieldset>
                    </div>
                </aside>

                {{-- Resultados --}}
                <main class="fr-results">
                    <div class="fr-head">
                        <h1 id="resultCount">{{ $todos->count() }} {{ $todos->count() === 1 ? 'vuelo encontrado' : 'vuelos encontrados' }}</h1>
                        <p>{{ request('departure') }} → {{ request('arrival') }} · Precios en euros por pasajero</p>
                    </div>

                    <div class="fr-stats">
                        @if ($masBarato)
                            <div class="fr-stat">
                                <span class="fr-stat-label">El más barato</span>
                                <strong>{{ $masBarato['price'] }} €</strong>
                                @if (isset($masBarato['total_duration']))<small>{{ $fmtDuracion($masBarato['total_duration']) }}</small>@endif
                            </div>
                        @endif
                        @if ($masRapido)
                            <div class="fr-stat">
                                <span class="fr-stat-label">El más rápido</span>
                                <strong>{{ $fmtDuracion($masRapido['total_duration']) }}</strong>
                                @if (isset($masRapido['price']))<small>{{ $masRapido['price'] }} €</small>@endif
                            </div>
                        @endif
                        @if ($salidaTemprana)
                            <div class="fr-stat">
                                <span class="fr-stat-label">Salida más temprana</span>
                                <strong>{{ date('H:i', strtotime($salidaTemprana['flights'][0]['departure_airport']['time'])) }}</strong>
                                <small>{{ $salidaTemprana['flights'][0]['departure_airport']['id'] ?? '' }}</small>
                            </div>
                        @endif
                    </div>

                    @if ($hayPrecios)
                        <section class="fr-card fr-prices">
                            <div class="fr-prices-head">
                                <div>
                                    <h2>Información de precios</h2>
                                    <p>Evolución del precio de esta ruta</p>
                                </div>
                                <div class="fr-prices-info">
                                    @if (isset($prices['price_level']))
                                        <span class="fr-pill" id="priceLevelPill">Nivel: <strong id="priceLevel">{{ $prices['price_level'] }}</strong></span>
                                    @endif
                                    @if (isset($prices['typical_price_range']))
                                        <span class="fr-pill">Rango típico: <strong><span id="priceRange">{{ implode(' - ', $prices['typical_price_range']) }}</span> €</strong></span>
                                    @endif
                                </div>
                            </div>
                            @if (isset($prices['price_history']))
                                <div class="fr-chart-date" id="selectedDate">Fecha seleccionada: {{ $fechaBase->format('d/m/y') }}</div>
                                <div class="fr-chart"><canvas id="priceChart"></canvas></div>
                            @endif
                        </section>
                    @endif

                    @foreach ($categorias as $titulo => $listaVuelos)
                        @if (!empty($listaVuelos))
                            <section class="fr-group" data-group>
                                <h2 class="fr-group-title">{{ $titulo }}</h2>

                                @foreach ($listaVuelos as $flight)
                                    @if (isset($flight['flights'][0]))
                                        @php
                                            $primerSegmento = $flight['flights'][0];
                                            $ultimoSegmento = end($flight['flights']);

                                            $tSalida = $primerSegmento['departure_airport']['time'] ?? null;
                                            $tLlegada = $ultimoSegmento['arrival_airport']['time'] ?? null;
                                            $horaSalida = $tSalida ? date('H:i', strtotime($tSalida)) : 'No disponible';
                                            $horaLlegada = $tLlegada ? date('H:i', strtotime($tLlegada)) : 'No disponible';
                                            $horaDecimal = $tSalida ? (int) date('G', strtotime($tSalida)) : '';

                                            // Días de diferencia entre salida y llegada
                                            $fechaSalida = $tSalida ? \Carbon\Carbon::parse($tSalida) : null;
                                            $fechaLlegada = $tLlegada ? \Carbon\Carbon::parse($tLlegada) : null;
                                            $diasDiferencia = null;
                                            if ($fechaSalida && $fechaLlegada) {
                                                $diasDiferencia = $fechaLlegada->day < $fechaSalida->day
                                                    ? 1
                                                    : $fechaLlegada->day - $fechaSalida->day;
                                            }

                                            $aerolinea = $primerSegmento['airline'] ?? 'Aerolínea no disponible';
                                            $numeros = collect($flight['flights'])->pluck('flight_number')->filter()->implode(' / ');
                                            $avion = collect($flight['flights'])->pluck('airplane')->filter()->unique()->implode(' / ');
                                            $escalas = $flight['layovers'] ?? [];
                                            $numEscalas = count($escalas);
                                            $precio = $flight['price'] ?? null;

                                            if ($numEscalas === 0) {
                                                $resumenEscalas = 'Directo';
                                            } elseif ($numEscalas === 1) {
                                                $e = $escalas[0];
                                                $resumenEscalas = '1 escala en ' . ($e['id'] ?? $e['name'] ?? '—')
                                                    . (isset($e['duration']) ? ' (' . $fmtDuracion($e['duration']) . ')' : '');
                                            } else {
                                                $resumenEscalas = $numEscalas . ' escalas: '
                                                    . collect($escalas)->map(fn($e) => $e['id'] ?? $e['name'] ?? '—')->implode(', ');
                                            }
                                        @endphp
                                        <a href="{{ isset($primerSegmento['flight_number']) ? route('flight.show', ['id' => $primerSegmento['flight_number']]) : '#' }}"
                                            class="fr-flight"
                                            data-flight
                                            data-price="{{ $precio ?? '' }}"
                                            data-stops="{{ $numEscalas }}"
                                            data-airline="{{ $aerolinea }}"
                                            data-hour="{{ $horaDecimal }}">
                                            <div class="fr-flight-badges">
                                                @if ($precio !== null && $precio === $precioMin)
                                                    <span class="fr-badge fr-badge--good"><i class="fas fa-tag"></i>Más barato</span>
                                                @endif
                                                @if (isset($flight['total_duration']) && $flight['total_duration'] === $duracionMin)
                                                    <span class="fr-badge fr-badge--sky"><i class="fas fa-bolt"></i>Más rápido</span>
                                                @endif
                                                @if ($numEscalas === 0)
                                                    <span class="fr-badge">Directo</span>
                                                @endif
                                            </div>

                                            <div class="fr-flight-main">
                                                <div class="fr-airline">
                                                    @if (isset($primerSegmento['airline_logo']))
                                                        <img src="{{ $primerSegmento['airline_logo'] }}" alt="Logo de {{ $aerolinea }}" width="36" height="36" loading="lazy">
                                                    @else
                                                        <span class="fr-airline-fallback"><i class="fas fa-plane"></i></span>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $aerolinea }}</strong>
                                                        @if ($numeros)<small>{{ $numeros }}</small>@endif
                                                    </div>
                                                </div>

                                                <div class="fr-times">
                                                    <div class="fr-time">
                                                        <strong>{{ $horaSalida }}</strong>
                                                        <small>{{ $primerSegmento['departure_airport']['id'] ?? '' }}</small>
                                                    </div>
                                                    <div class="fr-line">
                                                        <span class="fr-line-duration">
                                                            @if (isset($flight['total_duration'])){{ $fmtDuracion($flight['total_duration']) }}@else No disponible @endif
                                                        </span>
                                                        <span class="fr-line-bar"><i></i></span>
                                                        <span class="fr-line-stops">{{ $resumenEscalas }}</span>
                                                    </div>
                                                    <div class="fr-time fr-time--end">
                                                        <strong>{{ $horaLlegada }}
                                                            @if ($diasDiferencia === 1)
                                                                <sup title="Llega al día siguiente">+1</sup>
                                                            @elseif ($diasDiferencia === 2)
                                                                <sup title="Llega en dos días">+2</sup>
                                                            @endif
                                                        </strong>
                                                        <small>{{ $ultimoSegmento['arrival_airport']['id'] ?? '' }}</small>
                                                    </div>
                                                </div>

                                                <div class="fr-buy">
                                                    <small>Precio por pasajero</small>
                                                    @if ($precio === null)
                                                        <strong class="fr-price fr-price--na">No disponible</strong>
                                                    @else
                                                        <strong class="fr-price">{{ $precio }}<span>€</span></strong>
                                                    @endif
                                                    <span class="fr-select">Seleccionar <i class="fas fa-arrow-right"></i></span>
                                                </div>
                                            </div>

                                            @if ($avion)
                                                <div class="fr-flight-foot"><i class="fas fa-plane-up"></i>Flota: {{ $avion }}</div>
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </section>
                        @endif
                    @endforeach

                    <div class="fr-empty fr-empty--filters" id="filtersEmpty" hidden>
                        <i class="fas fa-filter-circle-xmark"></i>
                        <h2>Ningún vuelo coincide con los filtros</h2>
                        <button type="button" class="fr-cta" id="filtersEmptyReset">Restablecer filtros</button>
                    </div>
                </main>
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
        <script src="{{ asset('js/flight.js') }}?v={{ filemtime(public_path('js/flight.js')) }}"></script>
        <script src="{{ asset('js/flights-filters.js') }}?v={{ filemtime(public_path('js/flights-filters.js')) }}"></script>
    @endsection

@endsection
