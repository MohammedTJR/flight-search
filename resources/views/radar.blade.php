@extends('plantilla')

@section('titulo_pagina')
    FlyLow - Radar de Vuelos en Tiempo Real
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="{{ asset('css/radar.css') }}?v={{ filemtime(public_path('css/radar.css')) }}">
@endsection

@section('contenido')
    <div class="radar-stage">
        <div id="map">
            <div id="map-controls">
                <div class="map-style-selector">
                    <button id="toggle-map-style" class="btn-map-control" title="Cambiar estilo de mapa">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div id="map-style-options" class="map-style-options">
                        <div class="map-option" data-layer="osm">
                            <i class="fas fa-map"></i> Mapa estándar
                        </div>
                        <div class="map-option" data-layer="esri">
                            <i class="fas fa-satellite"></i> Satélite
                        </div>
                        <div class="map-option" data-layer="opentopomap">
                            <i class="fas fa-mountain"></i> Topográfico
                        </div>
                        <div class="map-option" data-layer="dark">
                            <i class="fas fa-moon"></i> Modo oscuro
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="radar-hud radar-header">
            <div class="radar-status">
                <span class="radar-live"><span class="pulse-dot"></span>Espacio aéreo en vivo</span>
                <span class="radar-stat"><strong id="total-flights">0</strong> vuelos</span>
                <span class="radar-stat"><strong id="total-countries">0</strong> países</span>
                <span class="radar-stat radar-stat--extra"><strong id="avg-altitude">0</strong> m alt. media</span>
                <span class="radar-stat radar-stat--extra"><strong id="avg-speed">0</strong> km/h vel. media</span>
            </div>

            <div class="radar-search">
                <i class="fas fa-magnifying-glass radar-search-icon"></i>
                <input type="text" id="flight-search" placeholder="Buscar por ICAO24 o callsign" autocomplete="off"
                    oninput="filterSearchResults()" onkeydown="if (event.key === 'Enter') searchFlight()">
                <button type="button" class="radar-search-btn" onclick="searchFlight()">Buscar</button>
                <div id="search-results" class="search-results"></div>
            </div>
        </div>

        {{-- Vuelo seleccionado: resumen siempre visible (hoja compacta en móvil) y detalles plegables --}}
        <aside id="flight-details-panel" aria-label="Vuelo seleccionado">
            <button type="button" class="panel-header" onclick="toggleExpanded()"
                aria-label="Mostrar u ocultar los detalles"></button>
            <div id="flight-summary"></div>
            <div id="flight-details-content"></div>
        </aside>

        <div class="refresh-info">
            <div><i class="fas fa-rotate"></i>Datos actualizados cada 60 segundos</div>
            <div id="next-update" class="refresh-sub"></div>
            <div id="last-update" class="refresh-sub">Última actualización: --:--:--</div>
        </div>

        <div class="loading-overlay" id="loading-overlay">
            <div class="spinner-border text-info mb-3" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Cargando datos de vuelos en tiempo real...</p>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('js/radar.js') }}?v={{ filemtime(public_path('js/radar.js')) }}"></script>
@endsection
