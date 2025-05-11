@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('titulo_pagina', 'Mi Perfil - FlyLow')

@section('contenido')
    <div class="profile-container bg-light fade-in">
        <!-- Header del perfil -->
        <div class="profile-header text-white py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="profile-avatar floating">
                            <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" alt="Foto perfil"
                                class="img-fluid rounded-circle shadow-lg border border-4 border-white">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-2">{{ $user->name }}</h1>
                        <p class="lead mb-1"><i class="fas fa-envelope me-2 profile-icon-hover"></i>{{ $user->email }}</p>
                        <p class="mb-0">
                            <i class="fas fa-calendar-alt me-2 profile-icon-hover"></i>
                            Miembro desde: {{ $user->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm btn-profile">
                            <i class="fas fa-edit me-2"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Columna izquierda -->
                <div class="col-lg-4">
                    <!-- Tarjeta de información básica -->
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0"><i class="fas fa-user-circle me-2 text-primary profile-icon-hover"></i>Información Básica</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled profile-info-list">
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-venus-mars me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Género</small>
                                            <p class="mb-0">{{ $user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'No especificado' }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-birthday-cake me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Fecha de nacimiento</small>
                                            <p class="mb-0">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'No especificada' }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Teléfono</small>
                                            <p class="mb-0">{{ $user->phone ?? 'No especificado' }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Columna central -->
                <div class="col-lg-4">
                    <!-- Tarjeta de ubicación -->
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary profile-icon-hover"></i>Ubicación</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled profile-info-list">
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-globe me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">País</small>
                                            <p class="mb-0">{{ $user->country ? config('countries')[$user->country] ?? $user->country : 'No especificado' }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-home me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Dirección</small>
                                            <p class="mb-0">{{ $user->address ?? 'No especificada' }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="col-lg-4">
                    <!-- Tarjeta de preferencias -->
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0"><i class="fas fa-cog me-2 text-primary profile-icon-hover"></i>Preferencias</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled profile-info-list">
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-language me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Idioma</small>
                                            <p class="mb-0">{{ $user->language ? ucfirst($user->language) : 'No especificado' }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Moneda</small>
                                            <p class="mb-0">{{ $user->currency ?? 'No especificada' }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-plane me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Preferencias de Viaje</small>
                                            <p class="mb-0 text-muted">Configura tus preferencias</p>
                                            <a href="#" class="btn btn-sm btn-outline-primary mt-2 btn-profile">
                                                <i class="fas fa-edit me-1"></i> Configurar
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection