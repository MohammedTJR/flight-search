@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('titulo_pagina', 'Mi Perfil - FlyLow')

@section('contenido')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Mi Perfil</h3>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-edit me-1"></i> Editar Perfil
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="profile-avatar mb-3">
                                    <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" alt="Foto perfil"
                                        class="img-fluid rounded-circle shadow">
                                </div>
                                <div class="user-basic-info">
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-1">{{ $user->email }}</p>
                                    <p class="text-muted small">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Miembro desde: {{ $user->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Información Personal
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="info-item mb-3">
                                                    <h6><i class="fas fa-venus-mars me-2"></i>Género</h6>
                                                    <p>{{ $user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'No especificado' }}
                                                    </p>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <h6><i class="fas fa-birthday-cake me-2"></i>Fecha de nacimiento</h6>
                                                    <p>{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'No especificada' }}
                                                    </p>
                                                </div>
                                                <div class="info-item">
                                                    <h6><i class="fas fa-phone me-2"></i>Teléfono</h6>
                                                    <p>{{ $user->phone ?? 'No especificado' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Ubicación</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="info-item mb-3">
                                                    <h6><i class="fas fa-globe me-2"></i>País</h6>
                                                    <p>{{ $user->country ? config('countries')[$user->country] ?? $user->country : 'No especificado' }}
                                                    </p>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <h6><i class="fas fa-home me-2"></i>Dirección</h6>
                                                    <p>{{ $user->address ?? 'No especificada' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Preferencias</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="info-item mb-3">
                                                    <h6><i class="fas fa-language me-2"></i>Idioma</h6>
                                                    <p>{{ $user->language ? ucfirst($user->language) : 'No especificado' }}
                                                    </p>
                                                </div>
                                                <div class="info-item">
                                                    <h6><i class="fas fa-money-bill-wave me-2"></i>Moneda</h6>
                                                    <p>{{ $user->currency ?? 'No especificada' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-plane me-2"></i>Preferencias de Viaje</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">Configura tus preferencias de viaje para obtener
                                                    mejores recomendaciones.</p>
                                                <a href="#" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit me-1"></i> Configurar preferencias
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection