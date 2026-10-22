@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-aero.css') }}?v={{ filemtime(public_path('css/auth-aero.css')) }}">
@endsection

@section('titulo_pagina', 'Restablecer Contraseña - FlyLow')

@section('contenido')
    @php
        // El enlace del correo trae el email: si viene, se muestra fijo
        $emailEnlace = request('email');
    @endphp

    <div class="au-page">
        <div class="au-glow"></div>

        <div class="au-card animate__animated animate__fadeIn">
            <div class="au-head">
                <div class="au-logo"><img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo"></div>
                <h1>Restablecer Contraseña</h1>
                <p>Escribe y confirma tu nueva contraseña para proteger tu cuenta y recuperar el acceso a FlyLow</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="au-form au-form--top needs-validation" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="au-field @error('email') is-invalid @enderror">
                    <label class="au-label" for="email">Correo electrónico</label>
                    <div class="au-input-wrap {{ $emailEnlace ? 'is-readonly' : '' }}">
                        <i class="far fa-envelope au-icon"></i>
                        <input type="email" class="au-input" name="email" id="email"
                            value="{{ old('email', $emailEnlace) }}" placeholder="nombre@ejemplo.com"
                            autocomplete="email" required {{ $emailEnlace ? 'readonly' : 'autofocus' }}>
                    </div>
                    @error('email')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="au-field @error('password') is-invalid @enderror">
                    <label class="au-label" for="password">
                        <span>Nueva contraseña</span>
                        <span class="au-hint" id="strength-label">Mínimo 8 caracteres</span>
                    </label>
                    <div class="au-input-wrap">
                        <i class="fas fa-lock au-icon"></i>
                        <input type="password" class="au-input" name="password" id="password" placeholder="••••••••"
                            autocomplete="new-password" minlength="8" required {{ $emailEnlace ? 'autofocus' : '' }}>
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
                        <span>Confirmar nueva contraseña</span>
                        <span class="au-hint" id="match-feedback" hidden></span>
                    </label>
                    <div class="au-input-wrap">
                        <i class="fas fa-lock au-icon"></i>
                        <input type="password" class="au-input" name="password_confirmation" id="password_confirmation"
                            placeholder="Vuelve a escribir la contraseña" autocomplete="new-password" required>
                        <button type="button" class="au-toggle" id="togglePasswordConfirm"
                            aria-label="Mostrar u ocultar contraseña">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="au-submit">
                    <span>Restablecer contraseña</span><i class="fas fa-key"></i>
                </button>
            </form>

            <p class="au-back">
                <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i>Volver al inicio de sesión</a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}?v={{ filemtime(public_path('js/auth.js')) }}"></script>
@endsection
