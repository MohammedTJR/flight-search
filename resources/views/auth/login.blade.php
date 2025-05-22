@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Iniciar Sesión - FlyLow')

@section('contenido')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card glass-card animate__animated animate__fadeIn">
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="login-logo mb-3">
                        <h3 class="text-primary mb-0">Iniciar Sesión</h3>
                        <p class="text-muted mt-2">Accede a tu cuenta para gestionar tus vuelos</p>
                    </div>
                    <div class="card-body px-5 py-4">
                        @if(session('status'))
                            <div class="alert alert-success animate__animated animate__fadeIn">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger animate__animated animate__headShake">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger animate__animated animate__headShake">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
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
                            
                            <div class="mb-4 position-relative">
                                <div class="form-floating password-field">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           id="password" 
                                           placeholder="Contraseña" 
                                           required>
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
                            
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Recordar sesión</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-primary small">¿Olvidaste tu contraseña?</a>
                                @endif
                            </div>
                            
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 shadow-sm">
                                    <span class="login-btn-text">Iniciar Sesión</span>
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="divider my-4">
                            <span class="divider-text">o continúa con</span>
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
                            <p class="text-muted">¿No tienes una cuenta? 
                                <a href="{{ route('register') }}" class="text-primary fw-semibold">Regístrate aquí</a>
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