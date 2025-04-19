@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('titulo_pagina', 'Iniciar Sesión - FlyLow')

@section('contenido')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center mb-0">Iniciar Sesión</h3>
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                    id="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordar sesión</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                        </form>

                        <div class="mt-4">
                            <p class="text-center mb-3">O inicia sesión con:</p>

                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <a href="{{ route('social.login', 'google') }}" class="btn btn-outline-danger">
                                    <i class="fab fa-google"></i> Google
                                </a>
                                <a href="{{ route('social.login', 'github') }}" class="btn btn-outline-dark">
                                    <i class="fab fa-github"></i> GitHub
                                </a>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="mb-2">¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection