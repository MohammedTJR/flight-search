@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('titulo_pagina', 'Mi Perfil - FlyLow')

@section('contenido')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Mi Perfil</h3>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="profile-avatar">
                                <img src={{ $user->avatar }} alt="Foto perfil">
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary mt-3">
                                Editar Perfil
                            </a>
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted mb-1">{{ $user->email }}</p>
                            <p class="text-muted">Miembro desde: {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Información de la cuenta</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Nombre:</h6>
                                    <p>{{ $user->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Email:</h6>
                                    <p>{{ $user->email }}</p>
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