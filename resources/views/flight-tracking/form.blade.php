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
            <span class="tk-eyebrow"><i class="fas fa-satellite-dish"></i>Nuevo seguimiento</span>
            <h1>Añadir vuelo a seguimiento</h1>
            <p>Busca un vuelo por su número y lo añadiremos a tu lista para que veas su posición.</p>

            @if(session('error'))
                <div class="tk-alert tk-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="tk-alert tk-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('flight.tracking.submit') }}" method="POST" class="tk-form">
                @csrf
                <label for="flight_number">Número de vuelo</label>
                <div class="tk-input">
                    <i class="fas fa-plane"></i>
                    <input type="text" id="flight_number" name="flight_number" value="{{ old('flight_number') }}"
                        placeholder="Ej: D84451" maxlength="10" required autofocus autocomplete="off">
                </div>
                <small>Introduce el número de vuelo sin espacios (ej: D84451)</small>

                <button type="submit" class="tk-submit"><i class="fas fa-magnifying-glass"></i>Buscar y añadir vuelo</button>
            </form>
        </div>
    </div>
@endsection
