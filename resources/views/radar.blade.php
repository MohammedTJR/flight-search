@extends('plantilla')

@section('titulo_pagina')
    FlyLow - Radar de Vuelos en Tiempo Real
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="{{ asset('css/radar.css') }}">
@endsection

@section('contenido')
    <div class="radar-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0"><i class="fas fa-plane me-2"></i>Radar de Vuelos en Tiempo Real</h4>
                <div id="last-update" class="text-muted small">Última actualización: --:--:--</div>
            </div>
            <div class="stats-container">
                <div class="stat-card">
                    <div id="total-flights" class="stat-value">0</div>
                    <div class="stat-label">Vuelos</div>
                </div>
                <div class="stat-card">
                    <div id="total-countries" class="stat-value">0</div>
                    <div class="stat-label">Países</div>
                </div>
                <div class="stat-card">
                    <div id="avg-altitude" class="stat-value">0</div>
                    <div class="stat-label">Alt. media (m)</div>
                </div>
                <div class="stat-card">
                    <div id="avg-speed" class="stat-value">0</div>
                    <div class="stat-label">Vel. media (km/h)</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0">
        <div id="map">
            <div id="map-controls">
                <div class="map-style-selector">
                    <button id="toggle-map-style" class="btn-map-control" title="Cambiar estilo de mapa">
                        <i class="fas fa-layer-group"></i>
                    </button>
                    <div id="map-style-options" class="map-style-options">
                        <div class="map-option" data-layer="osm">
                            <i class="fas fa-map"></i> Mapa Estándar
                        </div>
                        <div class="map-option" data-layer="esri">
                            <i class="fas fa-satellite"></i> Satélite
                        </div>
                        <div class="map-option" data-layer="opentopomap">
                            <i class="fas fa-mountain"></i> Topográfico
                        </div>
                        <div class="map-option" data-layer="dark">
                            <i class="fas fa-moon"></i> Modo Oscuro
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="flight-details-panel">
            <i class="fas fa-times close-panel" onclick="closeFlightDetails()"></i>
            <div id="flight-details-content">
                <div class="text-center py-5">
                    <p>Selecciona un vuelo en el mapa para ver sus detalles</p>
                </div>
            </div>
        </div>

        <div class="refresh-info">
            <i class="fas fa-sync-alt me-2"></i>Datos actualizados cada 60 segundos
            <div id="next-update" class="small text-muted"></div>
        </div>
    </div>

    <div class="loading-overlay" id="loading-overlay">
        <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <p>Cargando datos de vuelos en tiempo real...</p>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('js/radar.js') }}"></script>
@endsection