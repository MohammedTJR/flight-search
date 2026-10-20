@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}?v={{ filemtime(public_path('css/search.css')) }}">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('titulo_pagina', 'FlyLow')

@section('contenido')
    <section class="hero">
        <div class="hero-scenery" style="background-image: url('{{ asset('img/banner.jpg') }}');"></div>
        <div class="hero-glow hero-glow--top"></div>
        <div class="hero-glow hero-glow--side"></div>

        <div class="hero-content">
            <a href="{{ route('radar') }}" class="hero-chip animate__animated animate__fadeIn">
                <span class="pulse-dot"></span>Radar en vivo activo
            </a>
            <h1 class="hero-title animate__animated animate__fadeInDown">
                Encuentra tu próximo vuelo <br class="d-none d-sm-inline">
                <span class="hero-title-accent">al mejor precio</span>
            </h1>
            <p class="hero-subtitle animate__animated animate__fadeInUp">
                Compara cientos de aerolíneas y agencias en un solo lugar
            </p>
            <div class="hero-badges animate__animated animate__fadeInUp">
                <span class="hero-badge"><i class="fas fa-scale-balanced"></i>Comparación imparcial</span>
                <span class="hero-badge"><i class="fas fa-bolt bolt"></i>Precios en tiempo real</span>
                <span class="hero-badge"><i class="fas fa-circle-check"></i>Gratis y sin registro</span>
            </div>
        </div>
    </section>

    <div class="search-page">
        <div class="search-card">
            <form action="/flights" method="GET" id="flight-form">
                <div class="search-toolbar">
                    <div class="pill-toggle" role="radiogroup" aria-label="Escalas">
                        <input type="radio" class="pill-toggle-input" name="stops" id="con_escalas" value="0" checked>
                        <label class="pill-toggle-option" for="con_escalas">Con escalas</label>
                        <input type="radio" class="pill-toggle-input" name="stops" id="directo" value="1">
                        <label class="pill-toggle-option" for="directo">Solo directo</label>
                    </div>

                    <div class="dropdown pax">
                        <button type="button" class="pax-trigger" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-expanded="false" title="1 Adulto">
                            <i class="fas fa-users pax-icon"></i>
                            <span id="passengers-summary">1 pasajero · Económica</span>
                            <i class="fas fa-chevron-down pax-caret"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pax-menu">
                            <div class="pax-menu-title">Pasajeros</div>

                            <div class="passenger-type">
                                <div>
                                    <strong>Adultos</strong>
                                    <span class="passenger-age">12+ años</span>
                                </div>
                                <div class="quantity-selector">
                                    <button type="button" aria-label="Menos adultos"
                                        onclick="updateQuantity('adultos', -1, event)">−</button>
                                    <input type="number" id="adultos" name="adults" value="1" min="1" max="9" readonly>
                                    <button type="button" aria-label="Más adultos"
                                        onclick="updateQuantity('adultos', 1, event)">+</button>
                                </div>
                            </div>

                            <div class="passenger-type">
                                <div>
                                    <strong>Niños</strong>
                                    <span class="passenger-age">2-11 años</span>
                                </div>
                                <div class="quantity-selector">
                                    <button type="button" aria-label="Menos niños"
                                        onclick="updateQuantity('ninos', -1, event)">−</button>
                                    <input type="number" id="ninos" name="children" value="0" min="0" max="9" readonly>
                                    <button type="button" aria-label="Más niños"
                                        onclick="updateQuantity('ninos', 1, event)">+</button>
                                </div>
                            </div>

                            <div class="passenger-type">
                                <div>
                                    <strong>Bebés con asiento</strong>
                                    <span class="passenger-age">0-1 año</span>
                                </div>
                                <div class="quantity-selector">
                                    <button type="button" aria-label="Menos bebés con asiento"
                                        onclick="updateQuantity('bebes_asiento', -1, event)">−</button>
                                    <input type="number" id="bebes_asiento" name="infants_in_seat" value="0" min="0"
                                        max="9" readonly>
                                    <button type="button" aria-label="Más bebés con asiento"
                                        onclick="updateQuantity('bebes_asiento', 1, event)">+</button>
                                </div>
                            </div>

                            <div class="passenger-type">
                                <div>
                                    <strong>Bebés en regazo</strong>
                                    <span class="passenger-age">0-1 año</span>
                                </div>
                                <div class="quantity-selector">
                                    <button type="button" aria-label="Menos bebés en regazo"
                                        onclick="updateQuantity('bebes_regazo', -1, event)">−</button>
                                    <input type="number" id="bebes_regazo" name="infants_on_lap" value="0" min="0"
                                        max="9" readonly>
                                    <button type="button" aria-label="Más bebés en regazo"
                                        onclick="updateQuantity('bebes_regazo', 1, event)">+</button>
                                </div>
                            </div>

                            <div class="pax-class">
                                <label class="field-label" for="travel_class">Clase</label>
                                <select name="travel_class" id="travel_class" class="pax-select">
                                    <option value="1">Económica</option>
                                    <option value="2">Económica Premium</option>
                                    <option value="3">Business</option>
                                    <option value="4">Primera clase</option>
                                </select>
                            </div>

                            <div class="pax-footer">
                                <span>Máximo 9 pasajeros en total</span>
                                <button type="button" class="pax-apply" onclick="actualizarPasajeros()">Aplicar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="search-grid">
                    <div class="od-block">
                        <div class="field input-container">
                            <div class="field-head">
                                <label class="field-label" for="departure"><i
                                        class="fas fa-plane-departure"></i>Origen</label>
                                <span class="field-code" id="departure-badge" hidden></span>
                            </div>
                            <input type="text" id="departure" class="field-input"
                                placeholder="Ciudad o aeropuerto de salida" required autocomplete="off">
                            <input type="hidden" name="departure" id="departure_code">
                            <div id="departure-dropdown" class="autocomplete-dropdown"></div>
                        </div>

                        <button type="button" id="swap-airports" class="swap-btn"
                            title="Intercambiar origen y destino" aria-label="Intercambiar origen y destino">
                            <i class="fas fa-right-left"></i>
                        </button>

                        <div class="field input-container">
                            <div class="field-head">
                                <label class="field-label" for="arrival"><i
                                        class="fas fa-plane-arrival"></i>Destino</label>
                                <span class="field-code" id="arrival-badge" hidden></span>
                            </div>
                            <input type="text" id="arrival" class="field-input"
                                placeholder="Ciudad o aeropuerto de destino" required autocomplete="off">
                            <input type="hidden" name="arrival" id="arrival_code">
                            <div id="arrival-dropdown" class="autocomplete-dropdown"></div>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-head">
                            <label class="field-label" for="departure_date"><i
                                    class="fas fa-calendar-day"></i>Salida</label>
                        </div>
                        <input type="date" name="date" id="departure_date" class="field-input" required>
                    </div>

                    <button type="submit" class="search-submit">
                        <i class="fas fa-magnifying-glass"></i>Buscar vuelos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <section class="section container-fl">
        <div class="section-head">
            <div>
                <p class="eyebrow">Ventajas FlyLow</p>
                <h2 class="section-title">Tecnología de vuelo sin fricciones</h2>
            </div>
            <p class="section-lead">Compara tarifas, sigue los vuelos en directo y recibe avisos de precio, todo en un solo
                lugar.</p>
        </div>

        <div class="benefits">
            <article class="benefit-card">
                <div>
                    <div class="benefit-icon"><i class="fas fa-scale-balanced"></i></div>
                    <h3 class="benefit-title">Compara en segundos</h3>
                    <p class="benefit-text">Precios de cientos de aerolíneas y agencias, lado a lado.</p>
                </div>
                <a href="#flight-form" class="benefit-link">Buscar vuelos <i class="fas fa-arrow-right"></i></a>
            </article>

            <article class="benefit-card">
                <div>
                    <div class="benefit-icon"><i class="fas fa-satellite-dish"></i></div>
                    <h3 class="benefit-title">Radar en vivo <span class="status-pill">Activo</span></h3>
                    <p class="benefit-text">Sigue los vuelos en tiempo real sobre el mapa.</p>
                </div>
                <a href="{{ route('radar') }}" class="benefit-link">Abrir radar <i class="fas fa-arrow-right"></i></a>
            </article>

            <article class="benefit-card">
                <div>
                    <div class="benefit-icon"><i class="fas fa-bell"></i></div>
                    <h3 class="benefit-title">Alertas de precio</h3>
                    <p class="benefit-text">Te avisamos cuando baja el precio de tus vuelos favoritos.</p>
                </div>
                <a href="{{ route('favorites.show') }}" class="benefit-link">Ver mis favoritos <i
                        class="fas fa-arrow-right"></i></a>
            </article>
        </div>
    </section>

    <section class="section container-fl">
        <div class="radar-teaser">
            <div class="radar-teaser-grid"></div>
            <div class="radar-teaser-body">
                <div class="radar-teaser-copy">
                    <span class="live-badge"><span class="pulse-dot"></span>Radar de espacio aéreo en vivo</span>
                    <h2 class="radar-teaser-title">Sigue los vuelos en tiempo real sobre el mapa</h2>
                    <p class="radar-teaser-text">Consulta qué aviones están en el aire ahora mismo y sigue su posición
                        sin descargas ni instalación.</p>
                    <a href="{{ route('radar') }}" class="radar-teaser-cta">
                        <i class="fas fa-compass"></i>Explorar radar en vivo
                    </a>
                </div>
                <a href="{{ route('radar') }}" class="radar-teaser-visual" aria-label="Abrir el radar en vivo">
                    <img src="{{ asset('img/flight-radar-demo.png') }}" alt="Mapa del radar con aviones sobre España"
                        loading="lazy">
                </a>
            </div>
        </div>
    </section>

    <section class="section container-fl trust-section">
        <div class="trust-card">
            <div>
                <h3 class="trust-title">Planifica con total confianza</h3>
                <p class="trust-text">Encuentra los vuelos más económicos y planifica tu próximo viaje con FlyLow.</p>
            </div>
            <a href="{{ route('faq') }}" class="trust-link">Resolver mis dudas <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}?v={{ filemtime(public_path('js/search.js')) }}"></script>
    @endsection

@endsection
