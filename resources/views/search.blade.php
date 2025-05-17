@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('titulo_pagina', 'FlyLow')

@section('contenido')
    <div class="container-fluid p-0 search-page">
        <div class="text-center mb-4">
            <img src="{{ asset('img/banner.jpg') }}" class="img-fluid banner" alt="FlyLow Banner" style="height:20rem">
        </div>
    </div>
    <div class="container mt-5 mb-5">
        <h1 class="text-center">Buscar Vuelos</h1>
        <div class="card shadow p-4">
            <form action="/flights" method="GET" id="flight-form">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Escalas:</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="stops" id="con_escalas" value="0" checked>
                            <label class="btn btn-outline-primary border border-secondary w-50" for="con_escalas">Con
                                escalas</label>
                            <input type="radio" class="btn-check" name="stops" id="directo" value="1">
                            <label class="btn btn-outline-primary border border-secondary w-50"
                                for="directo">Directo</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pasajeros:</label>
                        <button type="button"
                            class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center"
                            data-bs-toggle="dropdown"
                            data-bs-placement="bottom"
                            title="1 Adulto"
                            style="min-height: 40px;"
                            aria-expanded="false">
                            <span><i class="fas fa-users me-2"></i>Seleccionar pasajeros</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu p-4" data-bs-auto-close="outside">
                            <div class="passenger-type mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-user me-2"></i>Adultos</strong>
                                        <div class="text-muted small">(12+ años)</div>
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
                                        <div class="text-muted small">(2-11 años)</div>
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
                                        <div class="text-muted small">(0-1 año)</div>
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
                                        <div class="text-muted small">(0-1 año)</div>
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

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Clase:</label>
                        <select name="travel_class" class="form-select">
                            <option value="1">Económica</option>
                            <option value="2">Económica Premium</option>
                            <option value="3">Business</option>
                            <option value="4">Primera clase</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de salida:</label>
                        <input type="date" name="date" id="departure_date" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 input-container">
                        <label class="form-label">Origen:</label>
                        <input type="text" id="departure" name="departure" class="form-control" required autocomplete="off">
                        <div id="departure-dropdown" class="autocomplete-dropdown"></div>
                    </div>
                    <div class="col-md-6 input-container">
                        <label class="form-label">Destino:</label>
                        <input type="text" id="arrival" name="arrival" class="form-control" required autocomplete="off">
                        <div id="arrival-dropdown" class="autocomplete-dropdown"></div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}"></script>
    @endsection
    
@endsection