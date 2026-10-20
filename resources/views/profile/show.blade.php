@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ filemtime(public_path('css/profile.css')) }}">
@endsection

@section('titulo_pagina', 'Mi Perfil - FlyLow')

@section('contenido')
    @php
        $generos = ['male' => 'Masculino', 'female' => 'Femenino', 'other' => 'Otro', 'prefer_not_to_say' => 'Prefiero no decir'];
        $idiomas = ['es' => 'Español', 'en' => 'Inglés', 'fr' => 'Francés'];
        $monedas = ['EUR' => 'Euro (EUR €)', 'USD' => 'Dólar (USD $)', 'GBP' => 'Libra (GBP £)'];

        $totalBusquedas = $user->searchHistory()->count();
        $totalSeguidos = $user->trackedFlights()->count();
    @endphp

    <div class="pf-page">
        <section class="pf-hero">
            <div class="pf-wrap pf-hero-inner">
                <a href="{{ route('profile.edit') }}" class="pf-avatar" title="Cambiar foto">
                    <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" alt="Foto de perfil">
                    <span><i class="fas fa-camera"></i></span>
                </a>

                <div class="pf-hero-info">
                    <h1>{{ $user->name }}
                        @if($user->email_verified_at)
                            <span class="pf-verified"><i class="fas fa-circle-check"></i>Verificado</span>
                        @endif
                    </h1>
                    <p>
                        <span><i class="far fa-envelope"></i>{{ $user->email }}</span>
                        <span><i class="far fa-calendar-check"></i>Miembro desde: {{ $user->created_at->format('d/m/Y') }}</span>
                    </p>
                    <div class="pf-pills">
                        <span><i class="fas fa-magnifying-glass"></i>{{ $totalBusquedas }} {{ $totalBusquedas === 1 ? 'búsqueda' : 'búsquedas' }}</span>
                        <span><i class="fas fa-plane"></i>{{ $totalSeguidos }} {{ $totalSeguidos === 1 ? 'vuelo seguido' : 'vuelos seguidos' }}</span>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="pf-btn pf-btn--light"><i class="fas fa-pen"></i>Editar perfil</a>
            </div>
        </section>

        <div class="pf-wrap pf-body">
            @if(session('success'))
                <div class="pf-alert pf-alert--ok" role="alert"><i class="fas fa-circle-check"></i>{{ session('success') }}</div>
            @endif

            <div class="pf-grid">
                {{-- Información básica --}}
                <section class="pf-card">
                    <h2><span class="ico"><i class="far fa-user"></i></span>Información básica</h2>
                    <ul class="pf-list">
                        <li>
                            <span class="ico"><i class="fas fa-venus-mars"></i></span>
                            <div><small>Género</small>
                                <strong>{{ $user->gender ? ($generos[$user->gender] ?? ucfirst(str_replace('_', ' ', $user->gender))) : 'No especificado' }}</strong></div>
                        </li>
                        <li>
                            <span class="ico"><i class="fas fa-cake-candles"></i></span>
                            <div><small>Fecha de nacimiento</small>
                                <strong>{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'No especificada' }}</strong></div>
                        </li>
                        <li>
                            <span class="ico"><i class="fas fa-phone"></i></span>
                            <div><small>Teléfono</small>
                                <strong>{{ $user->phone ?? 'No especificado' }}</strong></div>
                        </li>
                    </ul>
                </section>

                {{-- Ubicación --}}
                <section class="pf-card">
                    <h2><span class="ico"><i class="fas fa-location-dot"></i></span>Ubicación</h2>
                    <ul class="pf-list">
                        <li>
                            <span class="ico"><i class="fas fa-globe"></i></span>
                            <div><small>País</small>
                                <strong>{{ $user->country ? (config('countries')[$user->country] ?? $user->country) : 'No especificado' }}</strong></div>
                        </li>
                        <li>
                            <span class="ico"><i class="fas fa-house"></i></span>
                            <div><small>Dirección</small>
                                <strong class="{{ $user->address ? '' : 'is-empty' }}">{{ $user->address ?? 'No especificada' }}</strong></div>
                        </li>
                    </ul>
                </section>

                {{-- Preferencias --}}
                <section class="pf-card">
                    <h2><span class="ico"><i class="fas fa-sliders"></i></span>Preferencias</h2>
                    <ul class="pf-list">
                        <li>
                            <span class="ico"><i class="fas fa-language"></i></span>
                            <div><small>Idioma</small>
                                <strong>{{ $user->language ? ($idiomas[$user->language] ?? ucfirst($user->language)) : 'No especificado' }}</strong></div>
                        </li>
                        <li>
                            <span class="ico"><i class="fas fa-coins"></i></span>
                            <div><small>Moneda</small>
                                <strong>{{ $user->currency ? ($monedas[$user->currency] ?? $user->currency) : 'No especificada' }}</strong></div>
                        </li>
                        <li>
                            <span class="ico"><i class="fas fa-plane"></i></span>
                            <div><small>Preferencias de viaje</small>
                                <strong class="is-empty">Configura tus preferencias</strong></div>
                            <button type="button" class="pf-btn pf-btn--soft pf-btn--sm" id="openPreferencesModal">
                                <i class="fas fa-gear"></i>Configurar
                            </button>
                        </li>
                    </ul>
                </section>
            </div>

            {{-- Historial --}}
            <section class="pf-history" id="search-history" data-total="{{ $totalBusquedas }}">
                <header class="pf-history-head">
                    <h2><span class="ico"><i class="fas fa-clock-rotate-left"></i></span>
                        <span>Historial de búsquedas<small>Tus rutas consultadas recientemente</small></span></h2>
                    @if($totalBusquedas > 0)
                        <span class="pf-count">Mostrando <b id="showing-count">{{ min(5, $totalBusquedas) }}</b> de {{ $totalBusquedas }}
                            búsquedas</span>
                    @endif
                </header>

                @if($totalBusquedas > 0)
                    <div id="search-history-container">
                        @include('profile.partials.search-history-items', ['searchHistory' => $user->searchHistory()->take(5)->get()])
                    </div>
                    @if($totalBusquedas > 5)
                        <div class="pf-more" id="load-more-container">
                            <button type="button" class="pf-btn" id="load-more-btn" data-page="2">
                                <i class="fas fa-rotate"></i>Cargar más búsquedas
                            </button>
                        </div>
                    @endif
                @else
                    <div class="pf-empty">
                        <i class="fas fa-magnifying-glass"></i>
                        <h3>No hay búsquedas recientes</h3>
                        <p>Tus búsquedas aparecerán aquí</p>
                        <a href="{{ route('home') }}" class="pf-btn pf-btn--primary"><i class="fas fa-magnifying-glass"></i>Buscar vuelos</a>
                    </div>
                @endif
            </section>
        </div>
    </div>

    {{-- Modal de preferencias --}}
    <div class="pf-modal" id="preferencesModal" role="dialog" aria-modal="true" aria-labelledby="preferencesTitle">
        <div class="pf-modal-box">
            <header class="pf-modal-head">
                <h2 id="preferencesTitle"><i class="fas fa-gear"></i>Configuración de preferencias</h2>
                <button type="button" class="pf-modal-close" id="closePreferencesModal" aria-label="Cerrar">&times;</button>
            </header>

            <div class="pf-modal-body">
                <form id="preferencesForm" method="POST">
                    @csrf
                    <section class="pf-block">
                        <h3><i class="fas fa-plane"></i>Preferencias de viaje</h3>
                        <label class="pf-field-label" for="preferredClass">Clase de vuelo preferida</label>
                        <select class="pf-select" id="preferredClass" name="preferred_class">
                            <option value="economy" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'economy' ? 'selected' : '' }}>Económica</option>
                            <option value="premium_economy" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'premium_economy' ? 'selected' : '' }}>Premium Economy</option>
                            <option value="business" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'business' ? 'selected' : '' }}>Business</option>
                            <option value="first" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'first' ? 'selected' : '' }}>Primera Clase</option>
                        </select>
                        <label class="pf-switch">
                            <input type="hidden" name="direct_flights" value="0">
                            <input type="checkbox" id="directFlights" name="direct_flights" value="1"
                                {{ ($user->travel_preferences['direct_flights'] ?? false) ? 'checked' : '' }}>
                            <span class="track"></span><span class="txt">Preferir vuelos directos</span>
                        </label>
                    </section>

                    <section class="pf-block">
                        <h3><i class="far fa-bell"></i>Alertas de precios</h3>
                        <label class="pf-switch">
                            <input type="hidden" name="price_alerts" value="0">
                            <input type="checkbox" id="priceAlerts" name="price_alerts" value="1"
                                {{ ($user->notification_preferences['price_alerts'] ?? false) ? 'checked' : '' }}>
                            <span class="track"></span><span class="txt">Recibir alertas de precios</span>
                        </label>
                        <label class="pf-field-label" for="alertFrequency">Frecuencia de alertas</label>
                        <select class="pf-select" id="alertFrequency" name="alert_frequency">
                            <option value="immediate" {{ ($user->notification_preferences['alert_frequency'] ?? '') == 'immediate' ? 'selected' : '' }}>Inmediatamente</option>
                            <option value="daily" {{ ($user->notification_preferences['alert_frequency'] ?? '') == 'daily' ? 'selected' : '' }}>Resumen diario</option>
                            <option value="weekly" {{ ($user->notification_preferences['alert_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Resumen semanal</option>
                        </select>
                    </section>

                    <section class="pf-block">
                        <h3><i class="fas fa-cookie-bite"></i>Preferencias de cookies</h3>
                        <label class="pf-switch is-disabled">
                            <input type="checkbox" id="essentialCookies" checked disabled>
                            <span class="track"></span><span class="txt">Cookies esenciales (obligatorias)</span>
                        </label>
                        <label class="pf-switch">
                            <input type="hidden" name="analytics_cookies" value="0">
                            <input type="checkbox" id="analyticsCookies" name="analytics_cookies" value="1"
                                {{ ($user->notification_preferences['analytics_cookies'] ?? false) ? 'checked' : '' }}>
                            <span class="track"></span><span class="txt">Cookies de análisis</span>
                        </label>
                        <label class="pf-switch">
                            <input type="hidden" name="marketing_cookies" value="0">
                            <input type="checkbox" id="marketingCookies" name="marketing_cookies" value="1"
                                {{ ($user->notification_preferences['marketing_cookies'] ?? false) ? 'checked' : '' }}>
                            <span class="track"></span><span class="txt">Cookies de marketing</span>
                        </label>
                    </section>

                    <section class="pf-block">
                        <h3><i class="far fa-comments"></i>Preferencias de chat</h3>
                        <label class="pf-switch">
                            <input type="hidden" name="enable_chat" value="0">
                            <input type="checkbox" id="enableChat" name="enable_chat" value="1"
                                {{ ($user->notification_preferences['enable_chat'] ?? true) ? 'checked' : '' }}>
                            <span class="track"></span><span class="txt">Habilitar chat en vivo</span>
                        </label>
                        <label class="pf-switch">
                            <input type="hidden" name="chat_notifications" value="0">
                            <input type="checkbox" id="chatNotifications" name="chat_notifications" value="1"
                                {{ ($user->notification_preferences['chat_notifications'] ?? false) ? 'checked' : '' }}>
                            <span class="track"></span><span class="txt">Recibir notificaciones del chat</span>
                        </label>
                    </section>
                </form>
            </div>

            <footer class="pf-modal-foot">
                <button type="button" class="pf-btn" id="cancelPreferences">Cancelar</button>
                <button type="button" class="pf-btn pf-btn--primary" id="savePreferences">
                    <i class="fas fa-floppy-disk"></i>Guardar cambios
                </button>
            </footer>
        </div>
    </div>

    <div class="pf-toast" id="preferencesToast" role="status">
        <i class="fas fa-circle-check"></i><span>Preferencias guardadas correctamente</span>
        <button type="button" class="pf-toast-close" aria-label="Cerrar">&times;</button>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/profile.js') }}?v={{ filemtime(public_path('js/profile.js')) }}"></script>
@endsection
