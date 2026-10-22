@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-aero.css') }}?v={{ filemtime(public_path('css/auth-aero.css')) }}">
@endsection

@section('titulo_pagina', 'Verificar Email - FlyLow')

@section('contenido')
    <div class="au-page">
        <div class="au-glow"></div>

        <div class="au-card au-card--bar animate__animated animate__fadeIn">
            <div class="au-head">
                <div class="au-logo"><img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo"></div>
                <h1>Verifica tu Email</h1>
                <p>Te hemos enviado un correo de verificación</p>
            </div>

            @if(session('message') || session('status') === 'verification-link-sent')
                <div class="au-alert au-alert--ok">
                    <i class="fas fa-circle-check"></i>{{ session('message') ?? 'Te hemos enviado un nuevo enlace de verificación.' }}
                </div>
            @endif

            <div class="au-verify">
                <p>Antes de empezar a explorar y guardar tus vuelos, confirma tu cuenta haciendo clic en el enlace que enviamos a:</p>

                <span class="au-pill"><i class="far fa-envelope"></i><span>{{ Auth::user()->email }}</span></span>

                <div class="au-callout">
                    <i class="fas fa-circle-info"></i>
                    <p>¿No ves el mensaje en tu bandeja de entrada? Revisa la carpeta de <strong>spam</strong> o promociones, o solicita un nuevo enlace.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="au-form">
                @csrf
                <button type="submit" class="au-submit">
                    <i class="far fa-envelope"></i><span>Reenviar email de verificación</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="au-back">
                @csrf
                <button type="submit" class="au-linkbtn"><i class="fas fa-right-from-bracket"></i>Cerrar sesión</button>
            </form>
        </div>
    </div>
@endsection
