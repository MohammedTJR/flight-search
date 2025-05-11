@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Verificar Email - FlyLow')

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card glass-card animate__animated animate__fadeIn">
                <div class="card-header bg-transparent border-0 text-center py-4">
                    <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="login-logo mb-3">
                    <h3 class="text-primary mb-0">Verifica tu Email</h3>
                    <p class="text-muted mt-2">Te hemos enviado un correo de verificación</p>
                </div>

                <div class="card-body px-5 py-4">
                    @if (session('message'))
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p>Antes de continuar, por favor verifica tu correo electrónico haciendo clic en el enlace que te hemos enviado.</p>
                        <p class="mt-3">Si no has recibido el correo, puedes solicitar otro:</p>
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}" class="d-grid gap-3">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 shadow-sm">
                            <i class="fas fa-envelope me-2"></i>
                            Reenviar email de verificación
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection