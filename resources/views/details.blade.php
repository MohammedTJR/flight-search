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
            @if(session()->has('price_changed'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>¡El precio ha cambiado!</strong> El precio anterior era €{{ session('price_changed')['old_price'] }}
                    y ahora es €{{ session('price_changed')['new_price'] }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                                    value="{{ $flight['flights'][count($flight['flights']) - 1]['arrival_airport']['id'] ?? '' }}">
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


        <!-- Sección de opciones de reserva mejorada -->
        @if(!empty($bookingOptions))
            <section class="card shadow p-4 mb-4">
                <h4 class="mb-4"><i class="fas fa-shopping-cart"></i> Opciones de Reserva</h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Proveedor</th>
                                <th class="text-center">Precio</th>
                                <th>Detalles</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookingOptions as $option)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(isset($option['together']['airline_logos']) && count($option['together']['airline_logos']) > 0)
                                                        <img src="{{ $option['together']['airline_logos'][0] }}"
                                                            alt="{{ $option['together']['book_with'] }}" class="provider-logo me-3" width="40">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $option['together']['book_with'] ?? 'Proveedor' }}</strong>
                                                        @if(isset($option['together']['option_title']))
                                                            <div class="text-muted small">{{ $option['together']['option_title'] }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">€{{ number_format($option['together']['price'] ?? 0, 2) }}</span>
                                                @if(isset($option['together']['local_prices']))
                                                    <div class="text-muted small">
                                                        @foreach($option['together']['local_prices'] as $localPrice)
                                                            {{ $localPrice['currency'] }} {{ $localPrice['price'] }}
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($option['together']['extensions']) && is_array($option['together']['extensions']))
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($option['together']['extensions'] as $extension)
                                                            <li><small><i class="fas fa-check text-success me-1"></i> {{ $extension }}</small></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                @if(isset($option['together']['baggage_prices']))
                                                    <div class="mt-1">
                                                        <small><i class="fas fa-suitcase text-primary me-1"></i>
                                                            {{ implode(', ', $option['together']['baggage_prices']) }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(isset($option['together']['booking_request']['url']) && isset($option['together']['booking_request']['post_data']))
                                                                    <form method="POST" action="{{ $option['together']['booking_request']['url'] }}"
                                                                        target="_blank" id="bookingForm_{{ $loop->index }}">
                                                                        @php
                                                                            parse_str($option['together']['booking_request']['post_data'], $postFields);
                                                                        @endphp

                                                                        @foreach($postFields as $name => $value)
                                                                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                                                        @endforeach

                                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                                            <i class="fas fa-external-link-alt me-1"></i> Reservar
                                                                        </button>
                                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

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