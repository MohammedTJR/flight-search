@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Recuperar Contraseña - FlyLow')

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card glass-card animate__animated animate__fadeIn">
                <div class="card-header bg-transparent border-0 text-center py-4">
                    <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="login-logo mb-3">
                    <h3 class="text-primary mb-0">Recuperar Contraseña</h3>
                    <p class="text-muted mt-2">Te enviaremos un enlace para restablecer tu contraseña</p>
                </div>

                <div class="card-body px-5 py-4">
                    @if (session('status'))
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4 form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" id="email" value="{{ old('email') }}" 
                                   placeholder="Correo Electrónico" required autofocus>
                            <label for="email">Correo Electrónico</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 shadow-sm">
                                <span>Enviar enlace</span>
                                <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-primary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al inicio de sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection