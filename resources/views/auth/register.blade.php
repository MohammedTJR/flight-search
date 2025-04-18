@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Registro - FlyLow')

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center mb-0">Crear Cuenta</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo:</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico:</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña:</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </div>

                        <div class="mt-4">
                            <p class="text-center mb-3">O regístrate con:</p>
                            
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <a href="{{ route('social.login', 'google') }}" class="btn btn-outline-danger">
                                    <i class="fab fa-google"></i> Google
                                </a>
                                <a href="{{ route('social.login', 'facebook') }}" class="btn btn-outline-primary">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="{{ route('social.login', 'github') }}" class="btn btn-outline-dark">
                                    <i class="fab fa-github"></i> GitHub
                                </a>
                            </div>
                        </div>

                        <div class="text-center">
                            <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection