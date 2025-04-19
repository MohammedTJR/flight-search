@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detalles.css') }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('titulo_pagina', 'Detalles del Vuelo')

@section('contenido')
    <div class="container mt-5">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1>Detalles del Vuelo</h1>
            <div class="d-flex align-items-center gap-2">
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
                                    value="{{ $flight['flights'][0]['arrival_airport']['id'] ?? '' }}">
                                <input type="hidden" name="departure_date"
                                    value="{{ date('Y-m-d', strtotime($flight['flights'][0]['departure_airport']['time'] ?? '')) }}">
                                <input type="hidden" name="price" value="{{ $flight['price'] ?? 0 }}">
                                <input type="hidden" name="airline" value="{{ $flight['flights'][0]['airline'] ?? '' }}">
                                <button type="button" onclick="toggleFavorite(this)"
                                    class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-primary' }} btn-sm">
                                    <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                                    {{ $isFavorite ? 'Quitar de favoritos' : 'Guardar como favorito' }}
                                </button>
                            </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                        <i class="far fa-heart"></i> Guardar como favorito
                    </a>
                @endauth
                <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                    Volver
                </a>
            </div>
        </header>

        <section class="card shadow p-4 mb-4">
            <h3 class="text-center mb-4">
                Vuelo {{ $flight['flights'][0]['flight_number'] ?? 'N/A' }}
            </h3>
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $flight['airline_logo'] ?? '' }}" alt="Logo aerolínea" class="img-fluid" width="100">
                    <p class="mt-2">
                        <i class="fas fa-tag"></i> <strong>Precio:</strong> €{{ $flight['price'] ?? 'N/A' }}
                    </p>
                </div>
                <div class="col-md-9">
                    <p><i class="fas fa-plane-departure"></i> <strong>Aerolínea:</strong>
                        {{ $flight['flights'][0]['airline'] ?? 'N/A' }}</p>
                    <p><i class="fas fa-clock"></i> <strong>Duración total:</strong>
                        {{ intdiv($flight['total_duration'] ?? 0, 60) }}h {{ ($flight['total_duration'] ?? 0) % 60 }}min
                    </p>
                </div>
            </div>
        </section>

        <section class="card shadow p-4 mb-4">
            <h4><i class="fas fa-info-circle"></i> Detalles del Vuelo</h4>
            @foreach ($flight['flights'] as $segmento)
                <div class="mb-3 p-3 border rounded">
                    <h5>{{ $segmento['airline'] ?? 'N/A' }} ({{ $segmento['flight_number'] ?? 'N/A' }})</h5>
                    <p><i class="fas fa-plane-departure"></i> <strong>Salida:</strong>
                        {{ $segmento['departure_airport']['name'] ?? 'N/A' }}
                        ({{ $segmento['departure_airport']['id'] ?? 'N/A' }})
                        -
                        {{ !empty($segmento['departure_airport']['time']) ? date('H:i', strtotime($segmento['departure_airport']['time'])) : 'N/A' }}
                    </p>
                    <p><i class="fas fa-plane-arrival"></i> <strong>Llegada:</strong>
                        {{ $segmento['arrival_airport']['name'] ?? 'N/A' }}
                        ({{ $segmento['arrival_airport']['id'] ?? 'N/A' }}) -
                        {{ !empty($segmento['arrival_airport']['time']) ? date('H:i', strtotime($segmento['arrival_airport']['time'])) : 'N/A' }}
                    </p>
                    <p><i class="fas fa-clock"></i> <strong>Duración:</strong>
                        {{ intdiv($segmento['duration'] ?? 0, 60) }}h {{ ($segmento['duration'] ?? 0) % 60 }}min</p>
                    <p><i class="fas fa-chair"></i> <strong>Clase:</strong> {{ $segmento['travel_class'] ?? 'N/A' }}
                    </p>
                    <p><i class="fas fa-plane"></i> <strong>Avión:</strong> {{ $segmento['airplane'] ?? 'N/A' }}</p>
                    <p><i class="fas fa-running"></i> <strong>Espacio para piernas:</strong>
                        {{ $segmento['legroom'] ?? 'N/A' }}</p>
                    <p><i class="fas fa-gift"></i> <strong>Extras:</strong>
                        {{ implode(', ', $segmento['extensions'] ?? []) }}</p>
                </div>
            @endforeach
        </section>

        <section class="card shadow p-4 mb-4">
            <h4><i class="fas fa-leaf"></i> Emisiones de CO₂</h4>
            <div class="d-flex align-items-center">
                <div>
                    <p><i class="fas fa-smog"></i> <strong>Este vuelo:</strong>
                        {{ number_format($flight['carbon_emissions']['this_flight'] ?? 0) }}g CO₂</p>
                    <p><i class="fas fa-globe"></i> <strong>Promedio de la ruta:</strong>
                        {{ number_format($flight['carbon_emissions']['typical_for_this_route'] ?? 0) }}g CO₂</p>
                    <p><i class="fas fa-percentage"></i> <strong>Diferencia:</strong>
                        {{ $flight['carbon_emissions']['difference_percent'] ?? 0 }}%</p>
                </div>
            </div>
        </section>

        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/details.js') }}"></script>
    @endsection
@endsection