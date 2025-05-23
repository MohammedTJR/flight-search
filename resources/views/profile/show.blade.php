@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('titulo_pagina', 'Mi Perfil - FlyLow')

@section('contenido')
    <div class="profile-container bg-light fade-in">
        <!-- Header del perfil -->
        <div class="profile-header text-white py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="profile-avatar floating">
                            <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" alt="Foto perfil"
                                class="img-fluid rounded-circle shadow-lg border border-4 border-white">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-2">{{ $user->name }}</h1>
                        <p class="lead mb-1"><i class="fas fa-envelope me-2 profile-icon-hover"></i>{{ $user->email }}</p>
                        <p class="mb-0">
                            <i class="fas fa-calendar-alt me-2 profile-icon-hover"></i>
                            Miembro desde: {{ $user->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('profile.edit') }}"
                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm btn-profile">
                            <i class="fas fa-edit me-2"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Columna izquierda -->
                <div class="col-lg-4">
                    <!-- Tarjeta de información básica -->
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0"><i
                                    class="fas fa-user-circle me-2 text-primary profile-icon-hover"></i>Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled profile-info-list">
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-venus-mars me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Género</small>
                                            <p class="mb-0">
                                                @if($user->gender)
                                                    {{ ucfirst(str_replace('_', ' ', $user->gender)) }}
                                                @else
                                                    No especificado
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-birthday-cake me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Fecha de nacimiento</small>
                                            <p class="mb-0">
                                                @if($user->birth_date)
                                                    {{ $user->birth_date->format('d/m/Y') }}
                                                @else
                                                    No especificada
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Teléfono</small>
                                            <p class="mb-0">{{ $user->phone ?? 'No especificado' }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Columna central -->
                <div class="col-lg-4">
                    <!-- Tarjeta de ubicación -->
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0"><i
                                    class="fas fa-map-marker-alt me-2 text-primary profile-icon-hover"></i>Ubicación</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled profile-info-list">
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-globe me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">País</small>
                                            <p class="mb-0">
                                                {{ $user->country ? config('countries')[$user->country] ?? $user->country : 'No especificado' }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-home me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Dirección</small>
                                            <p class="mb-0">{{ $user->address ?? 'No especificada' }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="col-lg-4">
                    <!-- Tarjeta de preferencias -->
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0"><i class="fas fa-cog me-2 text-primary profile-icon-hover"></i>Preferencias
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled profile-info-list">
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-language me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Idioma</small>
                                            <p class="mb-0">
                                                {{ $user->language ? ucfirst($user->language) : 'No especificado' }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Moneda</small>
                                            <p class="mb-0">{{ $user->currency ?? 'No especificada' }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-plane me-3 text-muted profile-icon-hover"></i>
                                        <div>
                                            <small class="text-muted">Preferencias de Viaje</small>
                                            <p class="mb-0 text-muted">Configura tus preferencias</p>
                                            <button class="btn btn-sm btn-outline-primary mt-2 btn-profile" id="openPreferencesModal">
                                                <i class="fas fa-edit me-1"></i> Configurar
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2 text-primary"></i>Historial de búsquedas
                                </h5>
                                @if($user->searchHistory()->count() > 0)
                                    <small class="text-muted">
                                        Mostrando <span id="showing-count">{{ min(5, $user->searchHistory()->count()) }}</span>
                                        de {{ $user->searchHistory()->count() }} búsquedas
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($user->searchHistory()->count() > 0)
                                <div id="search-history-container">
                                    @include('profile.partials.search-history-items', ['searchHistory' => $user->searchHistory()->take(5)->get()])
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay búsquedas recientes</h5>
                                    <p class="text-muted">Tus búsquedas aparecerán aquí</p>
                                    <a href="{{ route('flights') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-search me-1"></i> Buscar vuelos
                                    </a>
                                </div>
                            @endif
                        </div>
                        @if($user->searchHistory()->count() > 5)
                            <div class="card-footer bg-white border-top text-center py-3" id="load-more-container">
                                <button class="btn btn-outline-primary rounded-pill px-4" id="load-more-btn" data-page="2">
                                    <i class="fas fa-sync me-2"></i>Cargar más búsquedas
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Preferencias personalizado -->
    <div class="custom-modal" id="preferencesModal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>Configuración de Preferencias
                </h5>
                <button type="button" class="custom-modal-close" id="closePreferencesModal">&times;</button>
            </div>
            <div class="custom-modal-body">
                <form id="preferencesForm" method="POST">
                    @csrf
                    <!-- Preferencias de Viaje -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-plane me-2"></i>Preferencias de Viaje</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Clase de vuelo preferida</label>
                                <select class="form-select" name="preferred_class">
                                    <option value="economy" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'economy' ? 'selected' : '' }}>Económica</option>
                                    <option value="premium_economy" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'premium_economy' ? 'selected' : '' }}>Premium Economy</option>
                                    <option value="business" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="first" {{ ($user->travel_preferences['preferred_class'] ?? '') == 'first' ? 'selected' : '' }}>Primera Clase</option>
                                </select>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="direct_flights" value="0">
                                <input class="form-check-input" type="checkbox" id="directFlights" name="direct_flights" value="1"
                                    {{ ($user->travel_preferences['direct_flights'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="directFlights">Preferir vuelos directos</label>
                            </div>
                        </div>
                    </div>

                    <!-- Alertas de Precios -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Alertas de Precios</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="price_alerts" value="0">
                                <input class="form-check-input" type="checkbox" id="priceAlerts" name="price_alerts" value="1"
                                    {{ ($user->notification_preferences['price_alerts'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="priceAlerts">Recibir alertas de precios</label>
                            </div>
                            <div class="form-group">
                                <label for="alertFrequency" class="form-label">Frecuencia de alertas</label>
                                <select class="form-select" id="alertFrequency" name="alert_frequency">
                                    <option value="immediate" {{ ($user->notification_preferences['alert_frequency'] ?? '') == 'immediate' ? 'selected' : '' }}>Inmediatamente</option>
                                    <option value="daily" {{ ($user->notification_preferences['alert_frequency'] ?? '') == 'daily' ? 'selected' : '' }}>Resumen diario</option>
                                    <option value="weekly" {{ ($user->notification_preferences['alert_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Resumen semanal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración de Cookies -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-cookie-bite me-2"></i>Preferencias de Cookies</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="essentialCookies" checked disabled>
                                <label class="form-check-label" for="essentialCookies">Cookies esenciales (obligatorias)</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="analytics_cookies" value="0">
                                <input class="form-check-input" type="checkbox" id="analyticsCookies" name="analytics_cookies" value="1"
                                    {{ ($user->notification_preferences['analytics_cookies'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="analyticsCookies">Cookies de análisis</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="hidden" name="marketing_cookies" value="0">
                                <input class="form-check-input" type="checkbox" id="marketingCookies" name="marketing_cookies" value="1"
                                    {{ ($user->notification_preferences['marketing_cookies'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="marketingCookies">Cookies de marketing</label>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración de Chat -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-comments me-2"></i>Preferencias de Chat</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="enable_chat" value="0">
                                <input class="form-check-input" type="checkbox" id="enableChat" name="enable_chat" value="1"
                                    {{ ($user->notification_preferences['enable_chat'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enableChat">Habilitar chat en vivo</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="hidden" name="chat_notifications" value="0">
                                <input class="form-check-input" type="checkbox" id="chatNotifications" name="chat_notifications" value="1"
                                    {{ ($user->notification_preferences['chat_notifications'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="chatNotifications">Recibir notificaciones del chat</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelPreferences">Cancelar</button>
                <button type="button" class="btn btn-primary" id="savePreferences">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    <!-- Toast de confirmación personalizado -->
    <div class="custom-toast" id="preferencesToast">
        <div class="toast-body">
            <i class="fas fa-check-circle me-2"></i> Preferencias guardadas correctamente
        </div>
        <button type="button" class="custom-toast-close">&times;</button>
    </div>
@endsection

@section('scripts')
    <script>
        // Gestión del modal personalizado
        const modal = document.getElementById('preferencesModal');
        const openBtn = document.getElementById('openPreferencesModal');
        const closeBtn = document.getElementById('closePreferencesModal');
        const cancelBtn = document.getElementById('cancelPreferences');
        
        if (openBtn) {
            openBtn.addEventListener('click', () => {
                modal.style.display = 'flex';
            });
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }
        
        // Cerrar modal al hacer clic fuera del contenido
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Cargar más búsquedas
        document.getElementById('load-more-btn')?.addEventListener('click', function () {
            const btn = this;
            const page = btn.dataset.page;
            const totalItems = {{ $user->searchHistory()->count() }};
            const currentShowing = parseInt(document.getElementById('showing-count').textContent);

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cargando...';

            fetch(`/profile/history?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('search-history-container').insertAdjacentHTML('beforeend', data.html);

                    const newShowing = Math.min(currentShowing + 5, totalItems);
                    document.getElementById('showing-count').textContent = newShowing;

                    if (newShowing >= totalItems) {
                        document.getElementById('load-more-container').remove();
                    } else {
                        btn.dataset.page = parseInt(page) + 1;
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-sync me-2"></i>Cargar más';
                    }
                });
        });

        // Gestión de preferencias
        document.getElementById('savePreferences')?.addEventListener('click', function() {
            const form = document.getElementById('preferencesForm');
            const formData = new FormData(form);
            
            // Mostrar spinner en el botón
            const btn = this;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
            btn.disabled = true;

            // Obtener el token CSRF
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/profile/preferences', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    // Mostrar mensaje de éxito
                    const toast = document.getElementById('preferencesToast');
                    toast.classList.add('show');
                    
                    // Ocultar el modal
                    modal.style.display = 'none';
                    
                    // Ocultar el toast después de 3 segundos
                    setTimeout(() => {
                        toast.classList.remove('show');
                    }, 3000);
                } else if (response.status === 422 && data.errors) {
                    // Mostrar errores de validación
                    let msg = 'Corrige los siguientes errores:\n';
                    for (const [field, errors] of Object.entries(data.errors)) {
                        msg += `- ${errors.join(', ')}\n`;
                    }
                    alert(msg);
                } else {
                    alert('Error al guardar las preferencias. Por favor, inténtalo de nuevo.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar las preferencias. Por favor, inténtalo de nuevo.');
            })
            .finally(() => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        });
        
        // Cerrar toast manualmente
        document.querySelector('.custom-toast-close')?.addEventListener('click', function() {
            document.getElementById('preferencesToast').classList.remove('show');
        });
    </script>
@endsection