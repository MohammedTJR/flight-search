@extends('plantilla')

@section('titulo_pagina', 'Preguntas Frecuentes | FlyLow - Comparador de vuelos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ filemtime(public_path('css/content.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}?v={{ filemtime(public_path('css/faq.css')) }}">
@endsection

@section('contenido')
    <section class="ct-hero">
        <div class="ct-wrap">
            <ol class="ct-crumbs">
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li><a href="{{ route('help') }}">Centro de ayuda</a></li>
                <li>FAQ</li>
            </ol>
            <span class="ct-chip"><i class="fas fa-circle-question"></i>Base de conocimiento</span>
            <h1 class="ct-title">Preguntas Frecuentes</h1>
            <p class="ct-lead">Encuentra respuestas rápidas sobre cómo usar FlyLow</p>

            <div class="faq-search ct-card">
                <div class="faq-search-box">
                    <i class="fas fa-magnifying-glass"></i>
                    <input type="search" id="faqSearch" placeholder="Buscar por término: «precios», «alertas», «contraseña»..."
                        autocomplete="off" aria-label="Buscar en las preguntas frecuentes">
                </div>
                <div class="faq-filters" role="group" aria-label="Filtrar por tema">
                    <span>Filtro rápido:</span>
                    <button type="button" class="is-active" data-filter="all">Todas</button>
                    <button type="button" data-filter="comparacion">Vuelos</button>
                    <button type="button" data-filter="favoritos">Alertas</button>
                    <button type="button" data-filter="tracker">Telemetría</button>
                    <button type="button" data-filter="cuenta">Cuenta</button>
                    <button type="button" data-filter="pagos">Pagos</button>
                </div>
            </div>
        </div>
    </section>

    <div class="ct-wrap ct-body">
        <div class="ct-layout">
            <aside class="ct-side">
                <nav class="ct-card ct-toc" aria-label="Índice temático">
                    <div class="ct-toc-head">Índice temático <b>5 temas</b></div>
                    <ol>
                        <li><a href="#comparacion"><i class="fas fa-right-left n"></i>Comparación de vuelos<span class="c">2</span></a></li>
                        <li><a href="#favoritos"><i class="far fa-heart n"></i>Favoritos y alertas<span class="c">1</span></a></li>
                        <li><a href="#tracker"><i class="fas fa-plane n"></i>Flight Tracker<span class="c">1</span></a></li>
                        <li><a href="#cuenta"><i class="far fa-user n"></i>Cuenta de usuario<span class="c">2</span></a></li>
                        <li><a href="#pagos"><i class="far fa-credit-card n"></i>Pagos y facturación<span class="c">2</span></a></li>
                    </ol>
                </nav>

                <div class="ct-note">
                    <strong><i class="fas fa-graduation-cap me-1"></i>Proyecto Fin de Ciclo (PFC)</strong>
                    Esta aplicación combina <b>funcionalidades reales implementadas</b>, <b>características planificadas para
                    desarrollo futuro</b> y <b>elementos ficticios</b> con fines demostrativos. Algunos de los datos
                    mostrados son simulados y no representan servicios reales.
                </div>
            </aside>

            <main class="faq-main">
                {{-- Comparación --}}
                <section id="comparacion" class="faq-module" data-module="comparacion">
                    <header class="faq-module-head">
                        <span class="ico"><i class="fas fa-right-left"></i></span>
                        <div><small>Módulo 01</small><h2>Comparación de Vuelos</h2></div>
                    </header>
                    <div class="faq-list" id="accordionComparacion">
                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComp1" aria-expanded="false">
                                <span class="q">Q.01</span><span class="t">¿Cómo garantizan que encuentran los mejores precios?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapseComp1" class="collapse" data-bs-parent="#accordionComparacion">
                                <div class="faq-a">
                                    <p>Nuestra tecnología escanea en tiempo real:</p>
                                    <ul>
                                        <li><strong>+120 aerolíneas</strong> y <strong>50+ OTAs</strong> (Online Travel Agencies)</li>
                                        <li>Precios con/sin equipaje para comparación justa</li>
                                        <li>Tarifas flexibles y reembolsables</li>
                                    </ul>
                                    <div class="faq-callout"><i class="fas fa-chart-line"></i>Usamos IA para predecir tendencias y sugerir el mejor momento para comprar.</div>
                                </div>
                            </div>
                        </article>

                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComp2" aria-expanded="false">
                                <span class="q">Q.02</span><span class="t">¿Por qué veo precios diferentes al finalizar la reserva?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapseComp2" class="collapse" data-bs-parent="#accordionComparacion">
                                <div class="faq-a">
                                    <p>Esto puede ocurrir porque:</p>
                                    <ol>
                                        <li>Algunas aerolíneas actualizan precios en tiempo real</li>
                                        <li>Hay cargos adicionales (equipaje, asientos) no incluidos inicialmente</li>
                                        <li>La tarifa exacta ya no está disponible</li>
                                    </ol>
                                    <div class="faq-callout"><i class="fas fa-rotate"></i>Siempre actualizamos los precios antes de redirigirte al sitio de reserva.</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                {{-- Favoritos --}}
                <section id="favoritos" class="faq-module" data-module="favoritos">
                    <header class="faq-module-head">
                        <span class="ico"><i class="far fa-heart"></i></span>
                        <div><small>Módulo 02</small><h2>Favoritos y Alertas</h2></div>
                    </header>
                    <div class="faq-list" id="accordionFavoritos">
                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFav1" aria-expanded="false">
                                <span class="q">Q.03</span><span class="t">¿Cómo funcionan las alertas de precio?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapseFav1" class="collapse" data-bs-parent="#accordionFavoritos">
                                <div class="faq-a">
                                    <div class="faq-cols">
                                        <div>
                                            <h3>Configuración</h3>
                                            <ol>
                                                <li>Guarda un vuelo en Favoritos</li>
                                                <li>Activa "Notificaciones"</li>
                                                <li>Establece tu precio deseado</li>
                                            </ol>
                                        </div>
                                        <div>
                                            <h3>Tipos de alerta</h3>
                                            <ul>
                                                <li><strong>Baja de precio</strong> (precio objetivo)</li>
                                                <li><strong>Tendencia a la baja</strong> (nuestra predicción)</li>
                                                <li><strong>Últimas plazas</strong> (cuando quedan pocos asientos)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="faq-callout"><i class="fas fa-bell"></i>Recibirás notificaciones por email y/o app (configurable).</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                {{-- Flight Tracker --}}
                <section id="tracker" class="faq-module" data-module="tracker">
                    <header class="faq-module-head">
                        <span class="ico"><i class="fas fa-plane"></i></span>
                        <div><small>Módulo 03</small><h2>Flight Tracker</h2></div>
                    </header>
                    <div class="faq-list" id="accordionTracker">
                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrack1" aria-expanded="false">
                                <span class="q">Q.04</span><span class="t">¿Qué precisión tiene el Flight Tracker?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapseTrack1" class="collapse" data-bs-parent="#accordionTracker">
                                <div class="faq-a">
                                    <p>Nuestros datos tienen <strong>95-99% de precisión</strong> gracias a:</p>
                                    <div class="faq-cols">
                                        <div class="faq-box">
                                            <h3><i class="fas fa-satellite-dish"></i>Fuentes</h3>
                                            <ul>
                                                <li>Red global ADS-B</li>
                                                <li>Datos MLAT</li>
                                                <li>APIs de aerolíneas</li>
                                            </ul>
                                        </div>
                                        <div class="faq-box">
                                            <h3><i class="far fa-clock"></i>Actualización</h3>
                                            <ul>
                                                <li>Cada 30-60 segundos en vuelo</li>
                                                <li>Retrasos en tierra: inmediatos</li>
                                                <li>Cancelaciones: confirmadas en 5-15 min</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                {{-- Cuenta --}}
                <section id="cuenta" class="faq-module" data-module="cuenta">
                    <header class="faq-module-head">
                        <span class="ico"><i class="far fa-user"></i></span>
                        <div><small>Módulo 04</small><h2>Cuenta de Usuario</h2></div>
                    </header>
                    <div class="faq-list" id="accordionCuenta">
                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCuenta1" aria-expanded="false">
                                <span class="q">Q.05</span><span class="t">¿Cómo cambio mi contraseña?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapseCuenta1" class="collapse" data-bs-parent="#accordionCuenta">
                                <div class="faq-a">
                                    <p>Para cambiar tu contraseña:</p>
                                    <ol>
                                        <li>Ve a "Mi cuenta" &gt; "Configuración de seguridad"</li>
                                        <li>Haz clic en "Cambiar contraseña"</li>
                                        <li>Ingresa tu contraseña actual y la nueva</li>
                                        <li>Confirma los cambios</li>
                                    </ol>
                                    <div class="faq-callout"><i class="fas fa-shield-halved"></i>Recomendamos usar una contraseña fuerte con al menos 8 caracteres, incluyendo números y símbolos.</div>
                                </div>
                            </div>
                        </article>

                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCuenta2" aria-expanded="false">
                                <span class="q">Q.06</span><span class="t">¿Cómo elimino mi cuenta?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapseCuenta2" class="collapse" data-bs-parent="#accordionCuenta">
                                <div class="faq-a">
                                    <p>Puedes eliminar tu cuenta siguiendo estos pasos:</p>
                                    <ol>
                                        <li>Ve a "Configuración de la cuenta"</li>
                                        <li>Selecciona "Eliminar cuenta"</li>
                                        <li>Confirma tu decisión</li>
                                    </ol>
                                    <div class="faq-callout faq-callout--warn"><i class="fas fa-triangle-exclamation"></i>Esta acción es irreversible y eliminará todos tus datos, favoritos y alertas.</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                {{-- Pagos --}}
                <section id="pagos" class="faq-module" data-module="pagos">
                    <header class="faq-module-head">
                        <span class="ico"><i class="far fa-credit-card"></i></span>
                        <div><small>Módulo 05</small><h2>Pagos y Facturación</h2></div>
                    </header>
                    <div class="faq-list" id="accordionPagos">
                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePagos1" aria-expanded="false">
                                <span class="q">Q.07</span><span class="t">¿Qué métodos de pago aceptan?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapsePagos1" class="collapse" data-bs-parent="#accordionPagos">
                                <div class="faq-a">
                                    <p>Aceptamos los siguientes métodos de pago:</p>
                                    <div class="faq-cols">
                                        <div>
                                            <h3>Tarjetas</h3>
                                            <ul>
                                                <li>Visa</li>
                                                <li>Mastercard</li>
                                                <li>American Express</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h3>Otros métodos</h3>
                                            <ul>
                                                <li>PayPal</li>
                                                <li>Transferencia bancaria</li>
                                                <li>Google Pay/Apple Pay</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="faq-item">
                            <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePagos2" aria-expanded="false">
                                <span class="q">Q.08</span><span class="t">¿Cómo obtengo un recibo o factura?</span><i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="collapsePagos2" class="collapse" data-bs-parent="#accordionPagos">
                                <div class="faq-a">
                                    <p>Para obtener tu factura:</p>
                                    <ol>
                                        <li>Inicia sesión en tu cuenta</li>
                                        <li>Ve a "Historial de pagos"</li>
                                        <li>Haz clic en "Descargar factura" junto al pago deseado</li>
                                    </ol>
                                    <div class="faq-callout"><i class="far fa-envelope"></i>También recibirás una copia por email automáticamente después de cada pago.</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <div class="faq-empty ct-card" id="faqEmpty" hidden>
                    <i class="fas fa-magnifying-glass"></i>
                    <h2>No hay preguntas que coincidan</h2>
                    <p>Prueba con otro término o consulta el <a href="{{ route('help') }}">Centro de ayuda</a>.</p>
                </div>

                <section class="ct-cta">
                    <h2>¿Sigues con dudas?</h2>
                    <p>Nuestro equipo de expertos en vuelos está listo para ayudarte personalmente.</p>
                    <div class="ct-cta-actions">
                        <a href="{{ route('help') }}" class="ct-btn ct-btn--primary"><i class="far fa-envelope"></i>Email</a>
                        <button type="button" class="ct-btn" onclick="openChat()"><i class="far fa-comments"></i>Chat en vivo</button>
                        <a href="tel:+34900123456" class="ct-btn"><i class="fas fa-phone"></i>+34 900 123 456</a>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection

@section('scripts')
    @include('partials.tawk')
    <script src="{{ asset('js/faq.js') }}?v={{ filemtime(public_path('js/faq.js')) }}"></script>
@endsection
