@extends('plantilla')

@section('titulo_pagina', 'Mis Vuelos Seguidos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ filemtime(public_path('css/content.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}?v={{ filemtime(public_path('css/tracking.css')) }}">
@endsection

@section('contenido')
    @php
        $total = $trackedFlights->count();
        $conPosicion = $trackedFlights->filter(fn($f) => !empty($f->position_data))->count();
        $ultima = $trackedFlights->max('last_checked_at');

        // Punto cardinal (8 vientos) para un rumbo en grados
        $cardinal = fn($g) => ['N', 'NE', 'E', 'SE', 'S', 'SO', 'O', 'NO'][((int) round($g / 45)) % 8];
    @endphp

    <div class="tk-wrap">
        <header class="tk-head">
            <div>
                <span class="tk-eyebrow"><i class="fas fa-satellite-dish"></i>Consola de seguimiento</span>
                <h1>Mis Vuelos Seguidos</h1>
                <p>Consulta la posición, la altitud y la velocidad de los vuelos que tienes en seguimiento.</p>
            </div>
            <a href="{{ route('flight.tracking.form') }}" class="ct-btn ct-btn--primary"><i class="fas fa-plus"></i>Añadir vuelo</a>
        </header>

        @if(session('success'))
            <div class="tk-alert tk-alert--ok"><i class="fas fa-circle-check"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="tk-alert tk-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ session('error') }}</div>
        @endif

        @if($trackedFlights->isEmpty())
            <div class="tk-empty ct-card">
                <i class="fas fa-plane-circle-check"></i>
                <h2>No tienes vuelos en seguimiento</h2>
                <p>Añade un vuelo por su número (por ejemplo, D84451) para ver su posición y su ruta.</p>
                <a href="{{ route('flight.tracking.form') }}" class="ct-btn ct-btn--primary"><i class="fas fa-plus"></i>Añade tu primer vuelo</a>
            </div>
        @else
            <div class="tk-stats">
                <div class="tk-stat ct-card">
                    <div><small>Vuelos seguidos</small><strong>{{ $total }}</strong></div>
                    <span class="ico"><i class="fas fa-plane"></i></span>
                </div>
                <div class="tk-stat ct-card">
                    <div><small>Con posición</small><strong>{{ $conPosicion }}</strong><em>de {{ $total }}</em></div>
                    <span class="ico"><i class="fas fa-location-dot"></i></span>
                </div>
                <div class="tk-stat ct-card">
                    <div><small>Última actualización</small><strong class="tk-stat-small">{{ $ultima ? $ultima->locale('es')->diffForHumans() : '—' }}</strong></div>
                    <span class="ico"><i class="far fa-clock"></i></span>
                </div>
            </div>

            <div class="tk-list" id="flightsAccordion">
                @foreach($trackedFlights as $flight)
                    @php
                        $d = $flight->flight_data ?? [];
                        $pos = $flight->position_data;
                        $ended = !empty($d['flight_ended']);
                        $estado = $ended ? 'Aterrizado' : ($pos ? 'En ruta' : 'Sin posición');
                        $estadoClase = $ended ? 'is-ended' : ($pos ? 'is-live' : 'is-none');
                    @endphp
                    <article class="tk-flight {{ $loop->first ? 'is-open' : '' }}">
                        <button class="tk-flight-head {{ $loop->first ? '' : 'collapsed' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $flight->id }}"
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $flight->id }}">
                            <span class="tk-tag">{{ $flight->flight_number }}</span>
                            <span class="tk-title">
                                <strong>{{ $d['callsign'] ?? 'N/A' }}</strong>
                                @if(!empty($d['operating_as']))<small>· {{ $d['operating_as'] }}</small>@endif
                            </span>
                            <span class="tk-status {{ $estadoClase }}">{{ $estado }}</span>
                            <span class="tk-updated">Actualizado: <b>{{ $flight->last_checked_at->locale('es')->diffForHumans() }}</b></span>
                            <i class="fas fa-chevron-down tk-chevron"></i>
                        </button>

                        <div id="collapse{{ $flight->id }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#flightsAccordion">
                            <div class="tk-flight-body">
                                {{-- Ruta --}}
                                <div class="tk-route">
                                    <div class="tk-route-end">
                                        <small>Origen</small>
                                        <strong>{{ $d['orig_icao'] ?? 'N/A' }}</strong>
                                        @if(!empty($d['datetime_takeoff']))
                                            <span>Despegue: {{ \Carbon\Carbon::parse($d['datetime_takeoff'])->format('d/m H:i') }} UTC</span>
                                        @endif
                                    </div>
                                    <div class="tk-route-mid"><span></span><i class="fas fa-plane"></i><span></span></div>
                                    <div class="tk-route-end tk-route-end--right">
                                        <small>Destino</small>
                                        <strong>{{ $d['dest_icao'] ?? 'N/A' }}</strong>
                                        @if(!empty($d['datetime_landed']))
                                            <span>Aterrizaje: {{ \Carbon\Carbon::parse($d['datetime_landed'])->format('d/m H:i') }} UTC</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tk-cols">
                                    <section>
                                        <h3><i class="fas fa-plane-departure"></i>Información del vuelo</h3>
                                        <dl class="tk-dl">
                                            <div><dt>Aerolínea</dt><dd>{{ $d['operating_as'] ?? 'N/A' }}</dd></div>
                                            <div><dt>Tipo de avión</dt><dd>{{ $d['type'] ?? 'N/A' }}</dd></div>
                                            <div><dt>Matrícula</dt><dd class="mono">{{ $d['reg'] ?? 'N/A' }}</dd></div>
                                            <div><dt>Origen</dt><dd>{{ $d['orig_icao'] ?? 'N/A' }}</dd></div>
                                            <div><dt>Destino</dt><dd>{{ $d['dest_icao'] ?? 'N/A' }}</dd></div>
                                        </dl>
                                    </section>

                                    <section>
                                        <h3><i class="far fa-compass"></i>Posición actual</h3>
                                        @if($pos)
                                            <p class="tk-location"><i class="fas fa-location-dot"></i>
                                                <span>{{ $pos['address'] ?? 'No se pudo determinar la ubicación' }}</span></p>
                                            <p class="tk-stamp">Última actualización:
                                                {{ \Carbon\Carbon::parse($pos['timestamp'])->format('d/m/Y H:i:s') }}</p>
                                            <div class="tk-tiles">
                                                <div>
                                                    <small>Altitud</small>
                                                    <strong>{{ number_format($pos['alt'] ?? 0, 0, ',', '.') }}<em>ft</em></strong>
                                                    <span>{{ number_format(round(($pos['alt'] ?? 0) * 0.3048), 0, ',', '.') }} m</span>
                                                </div>
                                                <div>
                                                    <small>Velocidad</small>
                                                    <strong>{{ $pos['gspeed'] ?? 0 }}<em>knots</em></strong>
                                                    <span>{{ round(($pos['gspeed'] ?? 0) * 1.852) }} km/h</span>
                                                </div>
                                                <div>
                                                    <small>Dirección</small>
                                                    <strong>{{ $pos['track'] ?? 0 }}°</strong>
                                                    <span>{{ $cardinal($pos['track'] ?? 0) }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="tk-warn"><i class="fas fa-triangle-exclamation"></i>No se pudo obtener la posición actual del vuelo.</div>
                                        @endif
                                    </section>
                                </div>

                                <div class="tk-actions">
                                    <form action="{{ route('flight.tracking.refresh', $flight->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="tk-btn"><i class="fas fa-rotate"></i>Actualizar</button>
                                    </form>
                                    <a href="{{ route('radar') }}" class="tk-btn tk-btn--dark"><i class="fas fa-satellite-dish"></i>Ver en el radar</a>
                                    <form action="{{ route('flight.tracking.destroy', $flight->id) }}" method="POST"
                                        onsubmit="return confirm('¿Dejar de seguir el vuelo {{ $flight->flight_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tk-btn tk-btn--danger"><i class="fas fa-trash-can"></i>Eliminar seguimiento</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
