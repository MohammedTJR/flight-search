@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-aero.css') }}?v={{ filemtime(public_path('css/auth-aero.css')) }}">
@endsection

@section('titulo_pagina', 'Recuperar Contraseña - FlyLow')

@section('contenido')
    <div class="au-page">
        <div class="au-glow"></div>

        <div class="au-card animate__animated animate__fadeIn">
            <div class="au-head">
                <div class="au-logo"><img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo"></div>
                <h1>Recuperar Contraseña</h1>
                <p>Te enviaremos un enlace para restablecer tu contraseña</p>
            </div>

            @if(session('status'))
                <div class="au-alert au-alert--ok"><i class="fas fa-circle-check"></i>{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="au-form au-form--top needs-validation" novalidate>
                @csrf
                <div class="au-field @error('email') is-invalid @enderror">
                    <label class="au-label" for="email">Correo electrónico</label>
                    <div class="au-input-wrap">
                        <i class="far fa-envelope au-icon"></i>
                        <input type="email" class="au-input" name="email" id="email" value="{{ old('email') }}"
                            placeholder="nombre@ejemplo.com" autocomplete="email" required autofocus>
                    </div>
                    @error('email')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="au-submit">
                    <span>Enviar enlace</span><i class="fas fa-paper-plane"></i>
                </button>
            </form>

            <p class="au-back">
                <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i>Volver al inicio de sesión</a>
            </p>
        </div>

        <p class="au-support">
            ¿No recibes el correo o tienes problemas?
            <a href="{{ route('help') }}">Contacta con soporte</a>
        </p>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}?v={{ filemtime(public_path('js/auth.js')) }}"></script>
@endsection
