@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}?v={{ filemtime(public_path('css/search.css')) }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('titulo_pagina', 'FlyLow')

@section('contenido')
    <section class="hero"
        style="background-image: linear-gradient(to bottom, rgba(10, 31, 56, 0.70) 0%, rgba(10, 31, 56, 0.72) 50%, rgba(10, 31, 56, 0.88) 100%), url('{{ asset('img/banner.jpg') }}');">
        <div class="hero-content">
            <h1 class="hero-title animate__animated animate__fadeInDown">Encuentra tu próximo vuelo al mejor precio</h1>
            <p class="hero-subtitle animate__animated animate__fadeInUp">
                Compara cientos de aerolíneas y agencias en un solo lugar
            </p>
            <div class="hero-badges animate__animated animate__fadeInUp">
                <span class="hero-badge"><i class="fas fa-circle-check"></i>Comparación imparcial</span>
                <span class="hero-badge"><i class="fas fa-bolt"></i>Precios en tiempo real</span>
                <span class="hero-badge"><i class="fas fa-gift"></i>Gratis y sin registro</span>
            </div>
        </div>
    </section>

    <div class="container search-page">
        <div class="card search-card shadow-lg">
            <h2 class="search-card-title"><i class="fas fa-magnifying-glass me-2"></i>Buscar vuelos</h2>
            <form action="/flights" method="GET" id="flight-form">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-route me-2"></i>Escalas</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="stops" id="con_escalas" value="0" checked>
                            <label class="btn btn-outline-primary w-50" for="con_escalas">Con escalas</label>
                            <input type="radio" class="btn-check" name="stops" id="directo" value="1">
                            <label class="btn btn-outline-primary w-50" for="directo">Directo</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-users me-2"></i>Pasajeros</label>
                        <button type="button"
                            class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center"
                            data-bs-toggle="dropdown"
                            data-bs-placement="bottom"
                            title="1 Adulto"
                            style="min-height: 48px;"
                            aria-expanded="false">
                            <span><i class="fas fa-users me-2"></i>Seleccionar pasajeros</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu passenger-menu" data-bs-auto-close="outside">
                            <div class="passenger-type mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-user me-2"></i>Adultos</strong>
                                        <span class="passenger-age">12+ años</span>
                                    </div>
                                    <div class="quantity-selector">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('adultos', -1, event)">-</button>
                                        <input type="number" id="adultos" name="adults" value="1" min="1" max="9" readonly>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('adultos', 1, event)">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="passenger-type mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-child me-2"></i>Niños</strong>
                                        <span class="passenger-age">2-11 años</span>
                                    </div>
                                    <div class="quantity-selector">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('ninos', -1, event)">-</button>
                                        <input type="number" id="ninos" name="children" value="0" min="0" max="9" readonly>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('ninos', 1, event)">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="passenger-type mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-baby me-2"></i>Bebés con asiento</strong>
                                        <span class="passenger-age">0-1 año</span>
                                    </div>
                                    <div class="quantity-selector">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('bebes_asiento', -1, event)">-</button>
                                        <input type="number" id="bebes_asiento" name="infants_in_seat" value="0" min="0"
                                            max="9" readonly>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('bebes_asiento', 1, event)">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="passenger-type mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-baby-carriage me-2"></i>Bebés en regazo</strong>
                                        <span class="passenger-age">0-1 año</span>
                                    </div>
                                    <div class="quantity-selector">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('bebes_regazo', -1, event)">-</button>
                                        <input type="number" id="bebes_regazo" name="infants_on_lap" value="0" min="0"
                                            max="9" readonly>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQuantity('bebes_regazo', 1, event)">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-info-circle me-1"></i>Máximo 9 pasajeros en total
                                </div>
                                <button type="button" class="btn btn-primary w-100"
                                    onclick="actualizarPasajeros()">Aplicar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-chair me-2"></i>Clase</label>
                        <select name="travel_class" class="form-select">
                            <option value="1">Económica</option>
                            <option value="2">Económica Premium</option>
                            <option value="3">Business</option>
                            <option value="4">Primera clase</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-calendar-day me-2"></i>Fecha de salida</label>
                        <input type="date" name="date" id="departure_date" class="form-control" required>
                    </div>
                </div>

                <div class="od-row">
                    <div class="od-field input-container">
                        <label class="form-label"><i class="fas fa-plane-departure me-2"></i>Origen</label>
                        <input type="text" id="departure" class="form-control"
                            placeholder="Ciudad o aeropuerto de salida" required autocomplete="off">
                        <input type="hidden" name="departure" id="departure_code">
                        <div id="departure-dropdown" class="autocomplete-dropdown"></div>
                    </div>
                    <button type="button" id="swap-airports" class="swap-btn"
                        title="Intercambiar origen y destino" aria-label="Intercambiar origen y destino">
                        <i class="fas fa-right-left"></i>
                    </button>
                    <div class="od-field input-container">
                        <label class="form-label"><i class="fas fa-plane-arrival me-2"></i>Destino</label>
                        <input type="text" id="arrival" class="form-control"
                            placeholder="Ciudad o aeropuerto de destino" required autocomplete="off">
                        <input type="hidden" name="arrival" id="arrival_code">
                        <div id="arrival-dropdown" class="autocomplete-dropdown"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100 search-submit">
                        <i class="fas fa-magnifying-glass me-2"></i>Buscar vuelos
                    </button>
                </div>
            </form>
        </div>

        <div class="row benefits g-4 mt-1">
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fas fa-scale-balanced"></i></div>
                    <h3 class="benefit-title">Compara en segundos</h3>
                    <p class="benefit-text">Precios de cientos de aerolíneas y agencias, lado a lado.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fas fa-satellite-dish"></i></div>
                    <h3 class="benefit-title">Radar en vivo</h3>
                    <p class="benefit-text">Sigue los vuelos en tiempo real sobre el mapa.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fas fa-bell"></i></div>
                    <h3 class="benefit-title">Alertas de precio</h3>
                    <p class="benefit-text">Te avisamos cuando baja el precio de tus vuelos favoritos.</p>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}?v={{ filemtime(public_path('js/search.js')) }}"></script>
    @endsection

@endsection
