@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}">
@endsection

@section('titulo_pagina', 'Favoritos')

@section('contenido')
    <div class="container py-4">
        <h2 class="mb-4">Tus Vuelos Favoritos</h2>

        @forelse ($favorites as $favorite)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5>
                        {{ $favorite->origin }} → {{ $favorite->destination }}
                        <small class="text-muted">({{ $favorite->airline }})</small>
                    </h5>
                    <p>
                        {{ \Carbon\Carbon::parse($favorite->departure_date)->format('d M Y') }} •
                        <span class="text-primary">{{ $favorite->price }}€</span>
                    </p>
                    <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">
                No tienes vuelos favoritos. Busca vuelos y guárdalos con el corazón ❤️
            </div>
        @endforelse
    </div>
@endsection