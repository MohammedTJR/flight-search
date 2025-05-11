@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Restablecer Contraseña - FlyLow')

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card glass-card animate__animated animate__fadeIn">
                <div class="card-header bg-transparent border-0 text-center py-4">
                    <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="login-logo mb-3">
                    <h3 class="text-primary mb-0">Restablecer Contraseña</h3>
                    <p class="text-muted mt-2">Ingresa tu nueva contraseña</p>
                </div>

                <div class="card-body px-5 py-4">
                    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4 form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" id="email" value="{{ old('email') }}" 
                                   placeholder="Correo Electrónico" required autofocus>
                            <label for="email">Correo Electrónico</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 position-relative">
                            <div class="form-floating password-field">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" id="password" placeholder="Nueva Contraseña" required>
                                <label for="password">Nueva Contraseña</label>
                                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y password-toggle"
                                        id="togglePassword">
                                    <i class="far fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 position-relative">
                            <div class="form-floating password-field">
                                <input type="password" class="form-control"
                                       name="password_confirmation" id="password_confirmation" 
                                       placeholder="Confirmar Nueva Contraseña" required>
                                <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y password-toggle"
                                        id="togglePasswordConfirm">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 shadow-sm">
                                <span>Restablecer Contraseña</span>
                                <i class="fas fa-key ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection