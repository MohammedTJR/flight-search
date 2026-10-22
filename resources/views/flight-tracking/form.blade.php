@extends('plantilla')

@section('titulo_pagina', 'Añadir Vuelo a Seguimiento')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ filemtime(public_path('css/content.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}?v={{ filemtime(public_path('css/tracking.css')) }}">
@endsection

@section('contenido')
    <div class="tk-wrap tk-wrap--narrow">
        <a href="{{ route('flight.tracking.index') }}" class="tk-back"><i class="fas fa-arrow-left"></i>Volver a mis vuelos</a>

        <div class="tk-form-card ct-card">
            <span class="tk-chip"><i class="fas fa-satellite-dish"></i>Nuevo seguimiento</span>
            <h1>Añadir vuelo a seguimiento</h1>
            <p>Busca un vuelo por su número de código y lo añadiremos a tu lista para que veas su posición, su altitud
                y su velocidad.</p>

            @if(session('error'))
                <div class="tk-alert tk-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="tk-alert tk-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('flight.tracking.submit') }}" method="POST" class="tk-form">
                @csrf
                <div class="tk-form__label">
                    <label for="flight_number">Número de vuelo</label>
                    <span>Ej: D84451, IB3812, VY3015</span>
                </div>
                <div class="tk-input">
                    <i class="fas fa-plane"></i>
                    <input type="text" id="flight_number" name="flight_number" value="{{ old('flight_number') }}"
                        placeholder="Ej: D84451" maxlength="10" required autofocus autocomplete="off" spellcheck="false">
                    <button type="button" class="tk-input__clear" id="clearFlight" title="Borrar" aria-label="Borrar"
                        hidden><i class="fas fa-xmark"></i></button>
                </div>
                <p class="tk-hint"><i class="fas fa-circle-info"></i>Introduce el número de vuelo sin espacios.</p>

                <button type="submit" class="tk-submit"><i class="fas fa-magnifying-glass"></i>Buscar y añadir vuelo</button>
            </form>
        </div>

        <div class="tk-know ct-card">
            <h2><i class="fas fa-bolt"></i>Qué verás de cada vuelo</h2>
            <div class="tk-know__grid">
                <div>
                    <i class="fas fa-location-dot"></i>
                    <b>Posición</b>
                    <span>Dónde está el avión ahora mismo.</span>
                </div>
                <div>
                    <i class="fas fa-gauge-high"></i>
                    <b>Altitud y velocidad</b>
                    <span>Datos de la última señal recibida.</span>
                </div>
                <div>
                    <i class="fas fa-route"></i>
                    <b>Ruta y estado</b>
                    <span>Origen, destino y si está en ruta o ya aterrizó.</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/tracking-form.js') }}?v={{ filemtime(public_path('js/tracking-form.js')) }}"></script>
@endsection
