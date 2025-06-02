@extends('plantilla')

@section('titulo_pagina', 'Preguntas Frecuentes | FlyLow - Comparador de vuelos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
@endsection

@section('contenido')
    <div class="faq-page py-5">
        <div class="container">
            <!-- Hero Section -->
            <div class="faq-hero text-center mb-5 py-5 rounded-4">
                <div class="container py-4">
                    <h1 class="display-4 fw-bold mb-3">Preguntas Frecuentes</h1>
                    <p class="lead mb-4">Encuentra respuestas rápidas sobre cómo usar FlyLow</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row g-4">
                <!-- FAQ Content -->
                <div class="col-12">
                    <!-- Comparación Section -->
                    <section id="comparacion" class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-exchange-alt fa-lg text-primary"></i>
                            </div>
                            <h2 class="mb-0">Comparación de Vuelos</h2>
                        </div>

                        <div class="accordion faq-accordion" id="accordionComparacion">
                            <!-- Item 1 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseComp1">
                                        ¿Cómo garantizan que encuentran los mejores precios?
                                    </button>
                                </h3>
                                <div id="collapseComp1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionComparacion">
                                    <div class="accordion-body">
                                        <p>Nuestra tecnología escanea en tiempo real:</p>
                                        <ul>
                                            <li><strong>+120 aerolíneas</strong> y <strong>50+ OTAs</strong> (Online Travel
                                                Agencies)</li>
                                            <li>Precios con/sin equipaje para comparación justa</li>
                                            <li>Tarifas flexibles y reembolsables</li>
                                        </ul>
                                        <div class="alert alert-light mt-3">
                                            <i class="fas fa-chart-line text-primary me-2"></i> Usamos IA para predecir
                                            tendencias y sugerir el mejor momento para comprar.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseComp2">
                                        ¿Por qué veo precios diferentes al finalizar la reserva?
                                    </button>
                                </h3>
                                <div id="collapseComp2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionComparacion">
                                    <div class="accordion-body">
                                        <p>Esto puede ocurrir porque:</p>
                                        <ol>
                                            <li>Algunas aerolíneas actualizan precios en tiempo real</li>
                                            <li>Hay cargos adicionales (equipaje, asientos) no incluidos inicialmente</li>
                                            <li>La tarifa exacta ya no está disponible</li>
                                        </ol>
                                        <p class="mb-0"><i class="fas fa-sync-alt text-info me-2"></i> Siempre actualizamos
                                            los precios antes de redirigirte al sitio de reserva.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Favoritos Section -->
                    <section id="favoritos" class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-danger bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-heart fa-lg text-danger"></i>
                            </div>
                            <h2 class="mb-0">Favoritos y Alertas</h2>
                        </div>

                        <div class="accordion faq-accordion" id="accordionFavoritos">
                            <!-- Item 1 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseFav1">
                                        ¿Cómo funcionan las alertas de precio?
                                    </button>
                                </h3>
                                <div id="collapseFav1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFavoritos">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Configuración:</h5>
                                                <ol>
                                                    <li>Guarda un vuelo en Favoritos</li>
                                                    <li>Activa "Notificaciones"</li>
                                                    <li>Establece tu precio deseado</li>
                                                </ol>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Tipos de alerta:</h5>
                                                <ul>
                                                    <li><strong>Baja de precio</strong> (precio objetivo)</li>
                                                    <li><strong>Tendencia a la baja</strong> (nuestra predicción)</li>
                                                    <li><strong>Últimas plazas</strong> (cuando quedan pocos asientos)</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="alert alert-light mt-3">
                                            <i class="fas fa-bell text-warning me-2"></i> Recibirás notificaciones por email
                                            y/o app (configurable).
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Flight Tracker Section -->
                    <section id="tracker" class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-info bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-plane fa-lg text-info"></i>
                            </div>
                            <h2 class="mb-0">Flight Tracker</h2>
                        </div>

                        <div class="accordion faq-accordion" id="accordionTracker">
                            <!-- Item 1 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTrack1">
                                        ¿Qué precisión tiene el Flight Tracker?
                                    </button>
                                </h3>
                                <div id="collapseTrack1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionTracker">
                                    <div class="accordion-body">
                                        <p>Nuestros datos tienen <strong>95-99% de precisión</strong> gracias a:</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="p-3 border rounded bg-light h-100">
                                                    <h5 class="fw-bold"><i
                                                            class="fas fa-satellite-dish text-primary me-2"></i> Fuentes
                                                    </h5>
                                                    <ul class="mb-0">
                                                        <li>Red global ADS-B</li>
                                                        <li>Datos MLAT</li>
                                                        <li>APIs de aerolíneas</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 border rounded bg-light h-100">
                                                    <h5 class="fw-bold"><i class="fas fa-clock text-info me-2"></i>
                                                        Actualización</h5>
                                                    <ul class="mb-0">
                                                        <li>Cada 30-60 segundos en vuelo</li>
                                                        <li>Retrasos en tierra: inmediatos</li>
                                                        <li>Cancelaciones: confirmadas en 5-15 min</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="cuenta" class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-user fa-lg text-success"></i>
                            </div>
                            <h2 class="mb-0">Cuenta de Usuario</h2>
                        </div>

                        <div class="accordion faq-accordion" id="accordionCuenta">
                            <!-- Item 1 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseCuenta1">
                                        ¿Cómo cambio mi contraseña?
                                    </button>
                                </h3>
                                <div id="collapseCuenta1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionCuenta">
                                    <div class="accordion-body">
                                        <p>Para cambiar tu contraseña:</p>
                                        <ol>
                                            <li>Ve a "Mi cuenta" > "Configuración de seguridad"</li>
                                            <li>Haz clic en "Cambiar contraseña"</li>
                                            <li>Ingresa tu contraseña actual y la nueva</li>
                                            <li>Confirma los cambios</li>
                                        </ol>
                                        <div class="alert alert-light mt-3">
                                            <i class="fas fa-shield-alt text-success me-2"></i> Recomendamos usar una
                                            contraseña fuerte con al menos 8 caracteres, incluyendo números y símbolos.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseCuenta2">
                                        ¿Cómo elimino mi cuenta?
                                    </button>
                                </h3>
                                <div id="collapseCuenta2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionCuenta">
                                    <div class="accordion-body">
                                        <p>Puedes eliminar tu cuenta siguiendo estos pasos:</p>
                                        <ol>
                                            <li>Ve a "Configuración de la cuenta"</li>
                                            <li>Selecciona "Eliminar cuenta"</li>
                                            <li>Confirma tu decisión</li>
                                        </ol>
                                        <p class="mb-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Esta
                                            acción es irreversible y eliminará todos tus datos, favoritos y alertas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Pagos Section -->
                    <section id="pagos" class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-credit-card fa-lg text-warning"></i>
                            </div>
                            <h2 class="mb-0">Pagos y Facturación</h2>
                        </div>

                        <div class="accordion faq-accordion" id="accordionPagos">
                            <!-- Item 1 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapsePagos1">
                                        ¿Qué métodos de pago aceptan?
                                    </button>
                                </h3>
                                <div id="collapsePagos1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionPagos">
                                    <div class="accordion-body">
                                        <p>Aceptamos los siguientes métodos de pago:</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Tarjetas</h5>
                                                <ul>
                                                    <li>Visa</li>
                                                    <li>Mastercard</li>
                                                    <li>American Express</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Otros métodos</h5>
                                                <ul>
                                                    <li>PayPal</li>
                                                    <li>Transferencia bancaria</li>
                                                    <li>Google Pay/Apple Pay</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapsePagos2">
                                        ¿Cómo obtengo un recibo o factura?
                                    </button>
                                </h3>
                                <div id="collapsePagos2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionPagos">
                                    <div class="accordion-body">
                                        <p>Para obtener tu factura:</p>
                                        <ol>
                                            <li>Inicia sesión en tu cuenta</li>
                                            <li>Ve a "Historial de pagos"</li>
                                            <li>Haz clic en "Descargar factura" junto al pago deseado</li>
                                        </ol>
                                        <p class="mb-0"><i class="fas fa-envelope text-info me-2"></i> También recibirás una
                                            copia por email automáticamente después de cada pago.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="card border-0 shadow-sm mt-4 bg-light">
                        <div class="card-body p-4 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-info-circle fa-lg text-primary me-2"></i>
                                <h5 class="mb-0 fw-bold">Nota importante sobre este proyecto</h5>
                            </div>
                            <p class="mb-0">
                                Esta aplicación forma parte de un Proyecto de Fin de Ciclo (PFC).
                                El contenido combina <strong>funcionalidades reales implementadas</strong>,
                                <strong>características planificadas para desarrollo futuro</strong>
                                y <strong>elementos ficticios</strong> con fines demostrativos.
                                Algunos de los datos mostrados son simulados y no representan servicios reales.
                            </p>
                        </div>
                    </div>

                    <!-- Contacto Final -->
                    <div class="card border-0 shadow-sm mt-5 bg-gradient-primary text-white overflow-hidden">
                        <div class="card-body p-5 position-relative">
                            <div class="position-absolute top-0 end-0 opacity-20">
                                <i class="fas fa-question-circle fa-10x"></i>
                            </div>
                            <div class="position-relative">
                                <h2 class="fw-bold mb-3">¿Sigues con dudas?</h2>
                                <p class="lead mb-4">Nuestro equipo de expertos en vuelos está listo para ayudarte
                                    personalmente.</p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('help') }}" class="btn btn-light px-4">
                                        <i class="fas fa-envelope me-2"></i> Email
                                    </a>
                                    <a href="javascript:void(Tawk_API.toggle())" class="btn btn-outline-light px-4">
                                        <i class="fas fa-comments me-2"></i> Chat en vivo
                                    </a>
                                    <a href="tel:+34900123456" class="btn btn-outline-light px-4">
                                        <i class="fas fa-phone me-2"></i> +34 900 123 456
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Scroll suave para anclas
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Configuración avanzada de Tawk.to
            var Tawk_API = Tawk_API || {};

            @auth
                Tawk_API.onLoad = function() {
                    Tawk_API.setAttributes({
                        'name': '{{ Auth::user()->name }}',
                        'email': '{{ Auth::user()->email }}',
                        'hash': '{{ hash_hmac("sha256", Auth::user()->email, config("app.key")) }}',
                        'user_id': '{{ Auth::id() }}',
                        'plan': '{{ Auth::user()->plan ?? "Free" }}'
                    }, function (error) { });

                    // Opcional: Mostrar mensaje de bienvenida personalizado
                    Tawk_API.addEvent('onChatMaximized', function () {
                        Tawk_API.sendMessage(`¡Hola {{ Auth::user()->name }}! ¿En qué podemos ayudarte hoy?`);
                    });
                };
            @endauth

            (function () {
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/{{ env("TAWKTO_PROPERTY_ID") }}/{{ env("TAWKTO_WIDGET_ID") }}';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        });
    </script>
@endsection