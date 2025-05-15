@extends('plantilla')

@section('titulo_pagina', 'Mis Vuelos Seguidos')

@section('contenido')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Mis Vuelos Seguidos</h4>
                        <a href="{{ route('flight.tracking.form') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Añadir Vuelo
                        </a>
                    </div>
                    <div class="card-body">
                        @if($trackedFlights->isEmpty())
                            <div class="alert alert-info">
                                No tienes vuelos en seguimiento. <a href="{{ route('flight.tracking.form') }}">Añade tu primer
                                    vuelo</a>.
                            </div>
                        @else
                            <div class="accordion" id="flightsAccordion">
                                @foreach($trackedFlights as $flight)
                                    <div class="accordion-item mb-3 border rounded">
                                        <h2 class="accordion-header" id="heading{{ $flight->id }}">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $flight->id }}"
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $flight->id }}">
                                                <div class="d-flex justify-content-between w-100 pe-3">
                                                    <div>
                                                        <span class="badge bg-primary me-2">{{ $flight->flight_number }}</span>
                                                        <span>{{ $flight->flight_data['callsign'] ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="text-muted small">
                                                        Actualizado: {{ $flight->last_checked_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $flight->id }}"
                                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                            aria-labelledby="heading{{ $flight->id }}" data-bs-parent="#flightsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5>Información del Vuelo</h5>
                                                        <ul class="list-group list-group-flush mb-3">
                                                            <li class="list-group-item">
                                                                <strong>Aerolínea:</strong>
                                                                {{ $flight->flight_data['operating_as'] ?? 'N/A' }}
                                                            </li>
                                                            <li class="list-group-item">
                                                                <strong>Tipo de Avión:</strong>
                                                                {{ $flight->flight_data['type'] ?? 'N/A' }}
                                                            </li>
                                                            <li class="list-group-item">
                                                                <strong>Matrícula:</strong>
                                                                {{ $flight->flight_data['reg'] ?? 'N/A' }}
                                                            </li>
                                                            <li class="list-group-item">
                                                                <strong>Origen:</strong>
                                                                {{ $flight->flight_data['orig_icao'] ?? 'N/A' }}
                                                            </li>
                                                            <li class="list-group-item">
                                                                <strong>Destino:</strong>
                                                                {{ $flight->flight_data['dest_icao'] ?? 'N/A' }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Posición Actual</h5>
                                                        @if($flight->position_data)
                                                            <ul class="list-group list-group-flush mb-3">
                                                                <li class="list-group-item">
                                                                    <strong>Última actualización:</strong> 
                                                                    {{ \Carbon\Carbon::parse($flight->position_data['timestamp'])->format('d/m/Y H:i:s') }}
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <strong>Ubicación:</strong> 
                                                                    {{ $flight->position_data['address'] ?? 'No se pudo determinar la ubicación' }}
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <strong>Altitud:</strong> {{ $flight->position_data['alt'] }} ft
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <strong>Velocidad:</strong> {{ $flight->position_data['gspeed'] }} knots
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <strong>Dirección:</strong> {{ $flight->position_data['track'] }}°
                                                                </li>
                                                            </ul>
                                                        @else
                                                            <div class="alert alert-warning">
                                                                No se pudo obtener la posición actual del vuelo.
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end mt-3">
                                                    <form action="{{ route('flight.tracking.refresh', $flight->id) }}" method="POST"
                                                        class="me-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-sync-alt me-1"></i> Actualizar
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('flight.tracking.destroy', $flight->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection