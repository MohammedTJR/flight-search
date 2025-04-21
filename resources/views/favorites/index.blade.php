@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('titulo_pagina', 'Favoritos')

@section('contenido')
    <div class="favorites-container">
        <div class="favorites-header">
            <h1 class="favorites-title">
                <i class="fas fa-heart text-danger me-2"></i> Tus Vuelos Favoritos
            </h1>
            <div class="favorites-count">
                <span class="badge bg-primary">{{ count($favorites) }} vuelos guardados</span>
            </div>
        </div>

        @if(count($favorites) > 0)
            <div class="favorites-grid">
                @foreach ($favorites as $favorite)
                    <div class="favorite-card">
                        <div class="favorite-image-container">
                            <img src="{{ $favorite->destination_image }}" alt="{{ $favorite->destination }}" class="favorite-image">
                            <div class="favorite-airline-badge">
                                <i class="fas fa-plane"></i> {{ $favorite->airline }}
                            </div>
                        </div>
                        <div class="favorite-card-body">
                            <div class="favorite-info">
                                <h3 class="favorite-route">
                                    {{ $favorite->origin }} <i class="fas fa-arrow-right mx-2 text-primary"></i>
                                    {{ $favorite->destination }}
                                </h3>
                                <div class="favorite-details">
                                    <div class="favorite-date">
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ \Carbon\Carbon::parse($favorite->departure_date)->format('d M Y') }}
                                    </div>
                                    <div class="favorite-price">
                                        <i class="fas fa-tag me-2"></i>
                                        <strong>{{ number_format($favorite->price, 2) }}€</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="favorite-actions">
                                <a href="{{ route('favorites.details', $favorite->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Ver detalles
                                </a>
                                <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-favorites text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="far fa-heart fa-4x text-muted"></i>
                </div>
                <h3 class="empty-title mb-3">No tienes vuelos favoritos</h3>
                <p class="empty-text mb-4">Busca vuelos y guárdalos con el ícono <i class="fas fa-heart text-danger"></i> para
                    verlos aquí</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i> Buscar vuelos
                </a>
            </div>
        @endif
    </div>
@endsection