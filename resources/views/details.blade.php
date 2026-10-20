@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detalles.css') }}?v={{ filemtime(public_path('css/detalles.css')) }}">
@endsection

@section('titulo_pagina', 'Detalles del Vuelo')

@section('contenido')
    @php
        $segmentos = array_values($flight['flights'] ?? []);
        $primero = $segmentos[0] ?? [];
        $ultimo = $segmentos ? $segmentos[count($segmentos) - 1] : [];
        $escalas = array_values($flight['layovers'] ?? []);

        $fmtDur = fn($m) => intdiv((int) $m, 60) . 'h ' . str_pad((int) $m % 60, 2, '0', STR_PAD_LEFT) . 'min';
        $fmtHora = fn($t) => $t ? date('H:i', strtotime($t)) : '—';
        $fmtDia = fn($t) => $t
            ? str_replace('.', '', \Carbon\Carbon::parse($t)->locale('es')->translatedFormat('j M Y'))
            : '';

        $origen = $primero['departure_airport'] ?? [];
        $destino = $ultimo['arrival_airport'] ?? [];
        $aerolineas = collect($segmentos)->pluck('airline')->filter()->unique()->values();
        $numeroVuelo = $primero['flight_number'] ?? 'N/A';
        $logo = $flight['airline_logo'] ?? ($primero['airline_logo'] ?? null);

        if (count($escalas) === 0) {
            $resumenEscalas = 'Directo';
        } elseif (count($escalas) === 1) {
            $resumenEscalas = '1 en ' . ($escalas[0]['id'] ?? $escalas[0]['name'] ?? '—')
                . (isset($escalas[0]['duration']) ? ' (' . $fmtDur($escalas[0]['duration']) . ')' : '');
        } else {
            $resumenEscalas = count($escalas) . ': ' . collect($escalas)->map(fn($e) => $e['id'] ?? $e['name'] ?? '—')->implode(', ');
        }

        $carbono = $flight['carbon_emissions'] ?? null;
        $opciones = collect($bookingOptions ?? []);
        $precioMinOpcion = $opciones->map(fn($o) => $o['together']['price'] ?? null)->filter()->min();
    @endphp

    <div class="dt-wrap">
        <div id="alert-area">
            @if(session()->has('price_changed'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>¡El precio ha cambiado!</strong> El precio anterior era €{{ session('price_changed')['old_price'] }}
                    y ahora es €{{ session('price_changed')['new_price'] }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <header class="dt-head">
            <div>
                <a href="javascript:history.back()" class="dt-back"><i class="fas fa-arrow-left"></i>Volver a los resultados</a>
                <h1>Detalles del vuelo <span>{{ $numeroVuelo }}</span></h1>
            </div>

            <div class="dt-actions">
                @auth
                    @php
                        $favorite = auth()->user()->favoriteFlights()
                            ->where('flight_id', $flight['flights'][0]['flight_number'])
                            ->where('airline', $flight['flights'][0]['airline'] ?? '')
                            ->where('departure_date', date('Y-m-d', strtotime($flight['flights'][0]['departure_airport']['time'] ?? '')))
                            ->first();

                        $isFavorite = !is_null($favorite);
                        $favoriteId = $isFavorite ? $favorite->id : null;
                    @endphp

                    <form id="favorite-form" method="POST"
                        action="{{ $isFavorite ? route('favorites.remove', $favoriteId) : route('favorites.add') }}">
                        @csrf
                        @if($isFavorite)
                            @method('DELETE')
                        @endif

                        <input type="hidden" name="flight_id" value="{{ $flight['flights'][0]['flight_number'] }}">
                        <input type="hidden" name="origin" value="{{ $flight['flights'][0]['departure_airport']['id'] ?? '' }}">
                        <input type="hidden" name="destination"
                            value="{{ $flight['flights'][count($flight['flights']) - 1]['arrival_airport']['id'] ?? '' }}">
                        <input type="hidden" name="departure_date"
                            value="{{ date('Y-m-d', strtotime($flight['flights'][0]['departure_airport']['time'] ?? '')) }}">
                        <input type="hidden" name="price" value="{{ $flight['price'] ?? 0 }}">
                        <input type="hidden" name="airline" value="{{ $flight['flights'][0]['airline'] ?? '' }}">

                        <input type="hidden" name="flight_details" value="{{ json_encode($flight) }}">
                        <input type="hidden" name="search_params" value="{{ json_encode(session('search_params', [])) }}">
                        <input type="hidden" name="adults" value="{{ session('search_params.adults', 1) }}">
                        <input type="hidden" name="children" value="{{ session('search_params.children', 0) }}">
                        <input type="hidden" name="infants_in_seat" value="{{ session('search_params.infants_in_seat', 0) }}">
                        <input type="hidden" name="infants_on_lap" value="{{ session('search_params.infants_on_lap', 0) }}">

                        <button type="button" onclick="toggleFavorite(this)"
                            class="dt-btn {{ $isFavorite ? 'dt-btn--danger' : '' }}">
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                            {{ $isFavorite ? 'Quitar de favoritos' : 'Guardar como favorito' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="dt-btn">
                        <i class="far fa-heart"></i>Guardar como favorito
                    </a>
                @endauth
                <a href="{{ route('radar') }}" class="dt-btn dt-btn--dark"><i class="fas fa-satellite-dish"></i>Ver en radar</a>
            </div>
        </header>

        {{-- Resumen --}}
        <section class="dt-card dt-hero">
            <div class="dt-hero-top">
                <div class="dt-hero-airline">
                    @if ($logo)
                        <img src="{{ $logo }}" alt="Logo de {{ $aerolineas->first() }}" width="56" height="56">
                    @else
                        <span class="dt-logo-fallback"><i class="fas fa-plane"></i></span>
                    @endif
                    <div>
                        <h2>{{ $aerolineas->implode(' + ') ?: 'Aerolínea no disponible' }}
                            <small>({{ $numeroVuelo }})</small></h2>
                        <p>{{ $origen['name'] ?? '' }} ({{ $origen['id'] ?? '—' }}) → {{ $destino['name'] ?? '' }} ({{ $destino['id'] ?? '—' }})</p>
                    </div>
                </div>

                <div class="dt-hero-price">
                    <span>Precio</span>
                    <strong>{{ $flight['price'] ?? 'N/A' }}<em>€</em></strong>
                    @if ($opciones->isNotEmpty())
                        <a href="#reservas" class="dt-btn dt-btn--dark">Ver opciones de reserva</a>
                    @endif
                </div>
            </div>

            <div class="dt-tiles">
                <div class="dt-tile">
                    <i class="far fa-clock"></i>
                    <div><small>Duración total</small><strong>{{ $fmtDur($flight['total_duration'] ?? 0) }}</strong></div>
                </div>
                <div class="dt-tile">
                    <i class="fas fa-route"></i>
                    <div><small>Escalas</small><strong>{{ $resumenEscalas }}</strong></div>
                </div>
                <div class="dt-tile">
                    <i class="fas fa-plane-departure"></i>
                    <div><small>Salida</small><strong>{{ $fmtHora($origen['time'] ?? null) }} · {{ $fmtDia($origen['time'] ?? null) }}</strong></div>
                </div>
                <div class="dt-tile">
                    <i class="fas fa-plane-arrival"></i>
                    <div><small>Llegada</small><strong>{{ $fmtHora($destino['time'] ?? null) }} · {{ $fmtDia($destino['time'] ?? null) }}</strong></div>
                </div>
            </div>
        </section>

        {{-- Itinerario --}}
        <section class="dt-card">
            <div class="dt-section-head">
                <h3><i class="fas fa-route"></i>Itinerario</h3>
                <span>Horarios en hora local de cada aeropuerto</span>
            </div>

            <ol class="dt-timeline">
                @foreach ($segmentos as $i => $segmento)
                    <li class="dt-segment">
                        <div class="dt-segment-head">
                            <span class="dt-segment-tag">Segmento {{ $i + 1 }}</span>
                            <strong>{{ $segmento['airline'] ?? 'N/A' }} · Vuelo {{ $segmento['flight_number'] ?? 'N/A' }}</strong>
                            @if (!empty($segmento['travel_class']))<span class="dt-segment-class">Clase {{ $segmento['travel_class'] }}</span>@endif
                        </div>

                        <div class="dt-leg">
                            <div class="dt-leg-end">
                                <strong>{{ $fmtHora($segmento['departure_airport']['time'] ?? null) }}</strong>
                                <span class="dt-leg-day">{{ $fmtDia($segmento['departure_airport']['time'] ?? null) }}</span>
                                <p>{{ $segmento['departure_airport']['name'] ?? 'N/A' }} ({{ $segmento['departure_airport']['id'] ?? 'N/A' }})</p>
                            </div>
                            <div class="dt-leg-mid">
                                <span>{{ $fmtDur($segmento['duration'] ?? 0) }} de vuelo</span>
                                <div class="dt-leg-bar"><i class="fas fa-plane"></i></div>
                                @if (!empty($segmento['overnight']))<small>Vuelo nocturno</small>@endif
                            </div>
                            <div class="dt-leg-end dt-leg-end--right">
                                <strong>{{ $fmtHora($segmento['arrival_airport']['time'] ?? null) }}</strong>
                                <span class="dt-leg-day">{{ $fmtDia($segmento['arrival_airport']['time'] ?? null) }}</span>
                                <p>{{ $segmento['arrival_airport']['name'] ?? 'N/A' }} ({{ $segmento['arrival_airport']['id'] ?? 'N/A' }})</p>
                            </div>
                        </div>

                        <div class="dt-facts">
                            <div><small>Aeronave</small><strong>{{ $segmento['airplane'] ?? 'N/A' }}</strong></div>
                            <div><small>Espacio para piernas</small><strong>{{ $segmento['legroom'] ?? 'N/A' }}</strong></div>
                        </div>

                        @if (!empty($segmento['extensions']))
                            <ul class="dt-chips">
                                @foreach ($segmento['extensions'] as $extra)
                                    <li>{{ $extra }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if (isset($escalas[$i]) && $i < count($segmentos) - 1)
                            @php $escala = $escalas[$i]; @endphp
                            <div class="dt-layover">
                                <i class="fas fa-person-walking-luggage"></i>
                                <div>
                                    <strong>Escala de {{ $fmtDur($escala['duration'] ?? 0) }} en {{ $escala['name'] ?? ($escala['id'] ?? '') }}@if(!empty($escala['id'])) ({{ $escala['id'] }})@endif</strong>
                                    @if (!empty($escala['overnight']))<small>Escala con noche incluida</small>@endif
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Opciones de reserva --}}
        @if ($opciones->isNotEmpty())
            <section class="dt-card" id="reservas">
                <div class="dt-section-head">
                    <h3><i class="fas fa-cart-shopping"></i>Opciones de reserva</h3>
                    <span>{{ $opciones->count() }} {{ $opciones->count() === 1 ? 'proveedor' : 'proveedores' }}</span>
                </div>

                <div class="dt-booking">
                    @foreach($opciones as $option)
                        @php
                            $o = $option['together'] ?? [];
                            $precioOpcion = $o['price'] ?? null;
                        @endphp
                        <div class="dt-option {{ $precioOpcion !== null && $precioOpcion === $precioMinOpcion ? 'is-best' : '' }}">
                            <div class="dt-option-provider">
                                @if(!empty($o['airline_logos']))
                                    <img src="{{ $o['airline_logos'][0] }}" alt="{{ $o['book_with'] ?? '' }}" width="40" height="40" loading="lazy">
                                @else
                                    <span class="dt-logo-fallback dt-logo-fallback--sm"><i class="fas fa-store"></i></span>
                                @endif
                                <div>
                                    <strong>{{ $o['book_with'] ?? 'Proveedor' }}
                                        @if ($precioOpcion !== null && $precioOpcion === $precioMinOpcion)<span class="dt-best">Mejor precio</span>@endif
                                    </strong>
                                    @if(isset($o['option_title']))<small>{{ $o['option_title'] }}</small>@endif
                                </div>
                            </div>

                            <div class="dt-option-details">
                                @if(isset($o['extensions']) && is_array($o['extensions']))
                                    <ul>
                                        @foreach($o['extensions'] as $extension)
                                            <li><i class="fas fa-check"></i>{{ $extension }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if(isset($o['baggage_prices']))
                                    <p><i class="fas fa-suitcase"></i>{{ implode(', ', $o['baggage_prices']) }}</p>
                                @endif
                            </div>

                            <div class="dt-option-price">
                                <strong>€{{ number_format($precioOpcion ?? 0, 2) }}</strong>
                                @if(isset($o['local_prices']))
                                    <small>
                                        @foreach($o['local_prices'] as $localPrice)
                                            {{ $localPrice['currency'] }} {{ $localPrice['price'] }}
                                        @endforeach
                                    </small>
                                @endif
                            </div>

                            <div class="dt-option-action">
                                @if(isset($o['booking_request']['url']) && isset($o['booking_request']['post_data']))
                                    <form method="POST" action="{{ $o['booking_request']['url'] }}" target="_blank"
                                        id="bookingForm_{{ $loop->index }}">
                                        @php
                                            parse_str($o['booking_request']['post_data'], $postFields);
                                        @endphp

                                        @foreach($postFields as $name => $value)
                                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                        @endforeach

                                        <button type="submit" class="dt-reserve">
                                            Reservar <i class="fas fa-arrow-up-right-from-square"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Emisiones de CO₂ --}}
        @if (!empty($carbono['this_flight']))
            @php
                $esteVuelo = (int) $carbono['this_flight'];
                $tipico = (int) ($carbono['typical_for_this_route'] ?? 0);
                $diferencia = $carbono['difference_percent'] ?? null;
                $porcentaje = $tipico > 0 ? min(100, round($esteVuelo / $tipico * 100)) : null;
            @endphp
            <section class="dt-card">
                <div class="dt-section-head">
                    <h3><i class="fas fa-leaf"></i>Emisiones de CO₂</h3>
                    @if ($diferencia !== null)
                        <span class="dt-pill {{ $diferencia <= 0 ? 'is-good' : 'is-bad' }}">
                            <i class="fas {{ $diferencia <= 0 ? 'fa-arrow-trend-down' : 'fa-arrow-trend-up' }}"></i>
                            {{ $diferencia > 0 ? '+' : '' }}{{ $diferencia }}% respecto al promedio de la ruta
                        </span>
                    @endif
                </div>

                <div class="dt-co2">
                    <div class="dt-co2-box">
                        <small>Este vuelo</small>
                        <strong>{{ number_format($esteVuelo / 1000, 0, ',', '.') }}<em>kg CO₂</em></strong>
                        <span>{{ number_format($esteVuelo, 0, ',', '.') }} g por pasajero</span>
                    </div>
                    @if ($tipico > 0)
                        <div class="dt-co2-box dt-co2-box--ref">
                            <small>Promedio de la ruta</small>
                            <strong>{{ number_format($tipico / 1000, 0, ',', '.') }}<em>kg CO₂</em></strong>
                            <span>{{ number_format($tipico, 0, ',', '.') }} g por pasajero</span>
                        </div>
                    @endif
                </div>

                @if ($porcentaje !== null)
                    <div class="dt-co2-bar" title="{{ $porcentaje }}% del promedio de la ruta">
                        <div class="dt-co2-fill {{ $esteVuelo <= $tipico ? '' : 'is-over' }}" style="width: {{ $porcentaje }}%"></div>
                    </div>
                @endif
            </section>
        @endif

        <div class="dt-faq">
            <span><i class="far fa-circle-question"></i>¿Dudas sobre el equipaje permitido o las escalas de este itinerario?</span>
            <a href="{{ route('faq') }}">Consultar preguntas frecuentes <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/details.js') }}?v={{ filemtime(public_path('js/details.js')) }}"></script>
@endsection
