@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Registro - FlyLow')

@section('contenido')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card glass-card animate__animated animate__fadeIn">
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="login-logo mb-3">
                        <h3 class="text-primary mb-0">Crear Cuenta</h3>
                        <p class="text-muted mt-2">Únete a nuestra comunidad de viajeros</p>
                    </div>
                    <div class="card-body px-5 py-4">
                        @if (session('success'))
                            <div class="alert alert-success animate__animated animate__fadeIn">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger animate__animated animate__headShake">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-4 form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    id="name" value="{{ old('name') }}" placeholder="Nombre Completo" required>
                                <label for="name">Nombre Completo</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                    id="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required>
                                <label for="email">Correo Electrónico</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 position-relative">
                                <div class="form-floating password-field">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" id="password" placeholder="Contraseña" required>
                                    <label for="password">Contraseña</label>
                                    <button type="button"
                                        class="btn position-absolute end-0 top-50 translate-middle-y password-toggle"
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
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="Confirmar Contraseña" required>
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                    <button type="button"
                                        class="btn position-absolute end-0 top-50 translate-middle-y password-toggle"
                                        id="togglePasswordConfirm">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="{{ route('terms') }}" class="text-primary">Términos y
                                        Condiciones</a>
                                </label>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 shadow-sm">
                                    <span class="register-btn-text">Registrarse</span>
                                    <i class="fas fa-user-plus ms-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="divider my-4">
                            <span class="divider-text">o regístrate con</span>
                        </div>

                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <a href="{{ route('social.login', 'google') }}" class="social-btn google-btn">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="{{ route('social.login', 'github') }}" class="social-btn github-btn">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted">¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}" class="text-primary fw-semibold">Inicia sesión</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection