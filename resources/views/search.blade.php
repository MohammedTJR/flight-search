@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('titulo_pagina', 'FlyLow')

@section('contenido')
    <div class="container-fluid p-0">
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
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Seleccionar Pasajeros
                        </button>
                        <div class="dropdown-menu w-100">
                            <div class="d-flex p-3">
                                <div class="me-3">
                                    <label>Adultos (12+ años)</label>
                                    <select id="adultos" name="adults" class="form-select">
                                        @for ($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Adulto(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="me-3">
                                    <label>Niños (2-11 años)</label>
                                    <select id="ninos" name="children" class="form-select">
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Niño(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="me-3">
                                    <label>Bebés (con asiento, 0-1 año)</label>
                                    <select id="bebes_asiento" name="infants_in_seat" class="form-select">
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="me-3">
                                    <label>Bebés (en regazo, 0-1 año)</label>
                                    <select id="bebes_regazo" name="infants_on_lap" class="form-select">
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                                        @endfor
                                    </select>
                                </div>
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