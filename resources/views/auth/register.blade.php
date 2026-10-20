@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-aero.css') }}?v={{ filemtime(public_path('css/auth-aero.css')) }}">
@endsection

@section('titulo_pagina', 'Registro - FlyLow')

@section('contenido')
    <div class="au-page">
        <div class="au-glow"></div>

        <div class="au-card au-card--wide animate__animated animate__fadeIn">
            <div class="au-head">
                <div class="au-logo"><img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo"></div>
                <h1>Crear Cuenta</h1>
                <p>Únete a nuestra comunidad de viajeros y sigue tus vuelos favoritos en tiempo real</p>
            </div>

            @if (session('success'))
                <div class="au-alert au-alert--ok">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="au-alert au-alert--error animate__animated animate__headShake">{{ session('error') }}</div>
            @endif

            <div class="au-social">
                <a href="{{ route('social.login', 'google') }}"><i class="fab fa-google"></i>Google</a>
                <a href="{{ route('social.login', 'github') }}"><i class="fab fa-github"></i>GitHub</a>
            </div>

            <div class="au-divider"><span>o completa tus datos</span></div>

            <form action="{{ route('register') }}" method="POST" class="au-form needs-validation" novalidate>
                @csrf
                <div class="au-field @error('name') is-invalid @enderror">
                    <label class="au-label" for="name">Nombre completo</label>
                    <div class="au-input-wrap">
                        <i class="far fa-user au-icon"></i>
                        <input type="text" class="au-input" name="name" id="name" value="{{ old('name') }}"
                            placeholder="Ej. Alejandro Navarro" required autofocus>
                    </div>
                    @error('name')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="au-field @error('email') is-invalid @enderror">
                    <label class="au-label" for="email">Correo electrónico</label>
                    <div class="au-input-wrap">
                        <i class="far fa-envelope au-icon"></i>
                        <input type="email" class="au-input" name="email" id="email" value="{{ old('email') }}"
                            placeholder="nombre@ejemplo.com" required>
                    </div>
                    @error('email')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="au-field @error('password') is-invalid @enderror">
                    <label class="au-label" for="password">
                        <span>Contraseña</span>
                        <span class="au-hint" id="strength-label">Mínimo 8 caracteres</span>
                    </label>
                    <div class="au-input-wrap">
                        <i class="fas fa-lock au-icon"></i>
                        <input type="password" class="au-input" name="password" id="password" minlength="8"
                            placeholder="Crea una contraseña segura" required>
                        <button type="button" class="au-toggle" id="togglePassword"
                            aria-label="Mostrar u ocultar contraseña">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <div class="au-strength" id="strength-bars" aria-hidden="true">
                        <i></i><i></i><i></i><i></i>
                    </div>
                    @error('password')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="au-field">
                    <label class="au-label" for="password_confirmation">
                        <span>Confirmar contraseña</span>
                        <span class="au-hint" id="match-feedback" hidden></span>
                    </label>
                    <div class="au-input-wrap">
                        <i class="fas fa-lock au-icon"></i>
                        <input type="password" class="au-input" name="password_confirmation" id="password_confirmation"
                            placeholder="Repite tu contraseña" required>
                        <button type="button" class="au-toggle" id="togglePasswordConfirm"
                            aria-label="Mostrar u ocultar contraseña">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <label class="au-check">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span>Acepto los <a href="{{ route('terms') }}">Términos y Condiciones</a> y la
                        <a href="{{ route('privacy') }}">Política de Privacidad</a> de FlyLow.</span>
                </label>

                <button type="submit" class="au-submit">
                    <span>Registrarse gratis</span><i class="fas fa-user-plus"></i>
                </button>
            </form>

            <p class="au-switch">¿Ya tienes una cuenta?
                <a href="{{ route('login') }}">Iniciar sesión</a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}?v={{ filemtime(public_path('js/auth.js')) }}"></script>
@endsection
