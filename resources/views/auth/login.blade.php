@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-aero.css') }}?v={{ filemtime(public_path('css/auth-aero.css')) }}">
@endsection

@section('titulo_pagina', 'Iniciar Sesión - FlyLow')

@section('contenido')
    <div class="au-page">
        <div class="au-glow"></div>

        <div class="au-card animate__animated animate__fadeIn">
            <div class="au-head">
                <div class="au-logo"><img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo"></div>
                <h1>Iniciar Sesión</h1>
                <p>Accede a tu cuenta para gestionar tus vuelos y tus alertas de precio</p>
            </div>

            @if(session('status'))
                <div class="au-alert au-alert--ok">{{ session('status') }}</div>
            @endif

            @if(session('error'))
                <div class="au-alert au-alert--error animate__animated animate__headShake">{{ session('error') }}</div>
            @endif

            {{-- Los errores de email y contraseña salen bajo su campo; aquí solo los demás (p. ej. demasiados intentos) --}}
            @php $otrosErrores = collect($errors->getMessages())->except(['email', 'password'])->flatten(); @endphp
            @if($otrosErrores->isNotEmpty())
                <div class="au-alert au-alert--error animate__animated animate__headShake">
                    <ul>
                        @foreach($otrosErrores as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="au-social">
                <a href="{{ route('social.login', 'google') }}"><i class="fab fa-google"></i>Google</a>
                <a href="{{ route('social.login', 'github') }}"><i class="fab fa-github"></i>GitHub</a>
            </div>

            <div class="au-divider"><span>o con tu correo</span></div>

            <form action="{{ route('login') }}" method="POST" class="au-form needs-validation" novalidate>
                @csrf
                <div class="au-field @error('email') is-invalid @enderror">
                    <label class="au-label" for="email">Correo electrónico</label>
                    <div class="au-input-wrap">
                        <i class="far fa-envelope au-icon"></i>
                        <input type="email" class="au-input" name="email" id="email" value="{{ old('email') }}"
                            placeholder="ejemplo@correo.com" required autofocus>
                    </div>
                    @error('email')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="au-field @error('password') is-invalid @enderror">
                    <label class="au-label" for="password">
                        <span>Contraseña</span>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        @endif
                    </label>
                    <div class="au-input-wrap">
                        <i class="fas fa-lock au-icon"></i>
                        <input type="password" class="au-input" name="password" id="password" placeholder="••••••••"
                            required>
                        <button type="button" class="au-toggle" id="togglePassword"
                            aria-label="Mostrar u ocultar contraseña">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="au-error">{{ $message }}</span>
                    @enderror
                </div>

                <label class="au-check">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Recordar sesión en este dispositivo</span>
                </label>

                <button type="submit" class="au-submit">
                    <span>Iniciar Sesión</span><i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <p class="au-switch">¿Aún no tienes cuenta?
                <a href="{{ route('register') }}">Regístrate gratis aquí</a>
            </p>
        </div>

        <div class="au-radar-chip">
            <span class="pulse-dot"></span>
            <strong>Radar en vivo</strong>
            <a href="{{ route('radar') }}">Ver mapa <i class="fas fa-arrow-up-right-from-square"></i></a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}?v={{ filemtime(public_path('js/auth.js')) }}"></script>
@endsection
