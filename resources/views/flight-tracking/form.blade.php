@extends('plantilla')

@section('titulo_pagina', 'Añadir Vuelo a Seguimiento')

@section('contenido')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Añadir Vuelo a Seguimiento</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('flight.tracking.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="flight_number" class="form-label">Número de Vuelo</label>
                                <input type="text" class="form-control form-control-lg" id="flight_number"
                                    name="flight_number" placeholder="Ej: D84451" required>
                                <div class="form-text">Introduce el número de vuelo sin espacios (ej: D84451)</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i> Buscar y Añadir Vuelo
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('flight.tracking.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver a mis vuelos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection