@extends('plantilla')

@section('titulo_pagina', 'Centro de Ayuda | FlyLow - Comparador de vuelos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ filemtime(public_path('css/content.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/help.css') }}?v={{ filemtime(public_path('css/help.css')) }}">
@endsection

@section('contenido')
    <section class="ct-hero hp-hero">
        <div class="ct-wrap">
            <span class="ct-chip"><i class="far fa-life-ring"></i>Soporte y guías</span>
            <h1 class="ct-title">Centro de Ayuda <em>FlyLow</em></h1>
            <p class="ct-lead">Aprende a encontrar las mejores ofertas de vuelos y aprovecha al máximo nuestro comparador</p>

            <form class="hp-search" id="helpSearchForm" role="search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" id="helpSearch" placeholder="¿Cómo podemos ayudarte?" autocomplete="off"
                    aria-label="Buscar ayuda">
                <button type="submit">Buscar</button>
            </form>
            <p class="hp-no-results" id="helpNoResults" hidden>No hemos encontrado ninguna guía con ese término. Prueba con
                «favoritos», «filtros» o «radar».</p>

            <div class="hp-popular">
                <span>Búsquedas populares:</span>
                <a href="#buscar-vuelos">Buscar vuelos</a>
                <a href="#favoritos">Favoritos</a>
                <a href="#tracker">Flight Tracker</a>
            </div>
        </div>
    </section>

    <div class="ct-wrap ct-body hp-body">
        {{-- Categorías --}}
        <div class="hp-cats">
            <a href="#buscar-vuelos" class="hp-cat ct-card">
                <span class="ico"><i class="fas fa-magnifying-glass-dollar"></i></span>
                <small>Módulo 01</small>
                <h2>Buscar vuelos</h2>
                <p>Aprende a encontrar las mejores ofertas y comparar precios</p>
                <b>Ver guía <i class="fas fa-arrow-right"></i></b>
            </a>
            <a href="#favoritos" class="hp-cat ct-card">
                <span class="ico"><i class="far fa-heart"></i></span>
                <small>Módulo 02</small>
                <h2>Favoritos</h2>
                <p>Guarda tus vuelos favoritos y recibe alertas de precio</p>
                <b>Ver guía <i class="fas fa-arrow-right"></i></b>
            </a>
            <a href="#tracker" class="hp-cat ct-card">
                <span class="ico"><i class="fas fa-plane-departure"></i></span>
                <small>Módulo 03</small>
                <h2>Flight Tracker</h2>
                <p>Sigue precios en tiempo real y recibe alertas</p>
                <b>Ver guía <i class="fas fa-arrow-right"></i></b>
            </a>
        </div>

        <h2 class="hp-section-title">Guías de uso</h2>

        {{-- Buscar vuelos --}}
        <section class="hp-guide ct-card" id="buscar-vuelos">
            <header class="hp-guide-head">
                <span class="ico"><i class="fas fa-magnifying-glass-dollar"></i></span>
                <div><small>Guía 01</small><h3>Cómo buscar y comparar vuelos</h3></div>
            </header>

            <div class="hp-two">
                <div>
                    <h4>1. Búsqueda básica</h4>
                    <p>Sigue estos pasos para encontrar vuelos:</p>
                    <ol>
                        <li>Ingresa tus ciudades de origen y destino</li>
                        <li>Selecciona fechas (o marca "Fechas flexibles")</li>
                        <li>Especifica número de pasajeros</li>
                        <li>Haz clic en "Buscar vuelos"</li>
                    </ol>
                    <div class="hp-tip"><i class="far fa-lightbulb"></i>
                        <span>Usa nuestro <strong>calendario de precios</strong> para ver los días más económicos para volar.</span>
                    </div>
                </div>
                <div>
                    <h4>2. Filtros avanzados</h4>
                    <p>Puedes refinar tus resultados con:</p>
                    <ul>
                        <li><strong>Aerolíneas:</strong> Compara precios entre diferentes compañías</li>
                        <li><strong>Escalas:</strong> Filtra por vuelos directos o con escalas</li>
                        <li><strong>Horarios:</strong> Busca por franjas horarias específicas</li>
                        <li><strong>Precio:</strong> Establece un rango de precios máximo/mínimo</li>
                    </ul>
                </div>
            </div>

            <div class="hp-cards">
                <div class="hp-mini">
                    <h5><i class="fas fa-circle-check"></i>Consejo profesional</h5>
                    <p>Busca en ventanas de incógnito para evitar precios inflados por cookies de seguimiento.</p>
                </div>
                <div class="hp-mini">
                    <h5><i class="far fa-bell"></i>¿Sabías que?</h5>
                    <p>Los martes por la tarde suelen ser el mejor momento para encontrar ofertas.</p>
                </div>
            </div>
        </section>

        {{-- Favoritos --}}
        <section class="hp-guide ct-card" id="favoritos">
            <header class="hp-guide-head">
                <span class="ico"><i class="far fa-heart"></i></span>
                <div><small>Guía 02</small><h3>Gestión de vuelos favoritos</h3></div>
            </header>

            <div class="hp-two hp-two--media">
                <div>
                    <h4>Guardar vuelos</h4>
                    <p>Para guardar un vuelo en tus favoritos:</p>
                    <ol>
                        <li>Busca vuelos como normalmente lo harías</li>
                        <li>En los resultados, haz clic en el icono <i class="far fa-heart hp-heart"></i> junto al vuelo que te
                            interese</li>
                        <li>Accede a tu sección de <a href="{{ route('favorites.show') }}">Favoritos</a> para ver todos tus
                            vuelos guardados</li>
                    </ol>
                </div>
                <img src="{{ asset('img/favoritos-demo.png') }}" alt="Guardar favoritos" class="hp-shot" loading="lazy">
            </div>

            <h4>Alertas de precio</h4>
            <p>Configura alertas para recibir notificaciones cuando bajen los precios:</p>
            <ul>
                <li>En tu lista de favoritos, activa "Notificaciones" para cada vuelo</li>
                <li>Establece tu precio deseado</li>
                <li>Recibirás un email cuando el precio baje a tu nivel deseado</li>
            </ul>
            <div class="hp-tip"><i class="fas fa-circle-info"></i>
                <span>Las alertas de precio también están disponibles directamente desde los resultados de búsqueda.</span>
            </div>
        </section>

        {{-- Flight Tracker --}}
        <section class="hp-guide hp-guide--dark" id="tracker">
            <header class="hp-guide-head">
                <span class="ico"><i class="fas fa-plane-departure"></i></span>
                <div><small>Guía 03</small><h3>Flight Tracker: seguimiento de aviones en tiempo real</h3></div>
            </header>

            <div class="hp-two">
                <div>
                    <h4>¿Qué es nuestro Flight Tracker?</h4>
                    <p>Una herramienta avanzada que te permite:</p>
                    <ul>
                        <li><strong>Seguir aviones en vuelo</strong> en tiempo real en un mapa interactivo</li>
                        <li>Ver <strong>datos de vuelo</strong>: altitud, velocidad, ruta y retrasos</li>
                        <li>Consultar <strong>información histórica</strong> de rutas y aerolíneas</li>
                        <li>Recibir <strong>alertas</strong> sobre cambios en vuelos específicos</li>
                    </ul>
                    <a href="{{ route('radar') }}" class="hp-dark-btn"><i class="fas fa-satellite-dish"></i>Abrir el radar en vivo</a>
                </div>
                <figure class="hp-shot-dark">
                    <img src="{{ asset('img/flight-radar-demo.png') }}" alt="Flight Tracker Demo" loading="lazy">
                    <figcaption>Vista del Flight Tracker mostrando aviones en tiempo real sobre Europa</figcaption>
                </figure>
            </div>

            <h4>Cómo usar el Flight Tracker</h4>
            <div class="hp-steps">
                <div class="hp-step">
                    <span class="num">1</span>
                    <h5>Buscar un vuelo</h5>
                    <p>Ingresa:</p>
                    <ul>
                        <li>Número de vuelo (ej: IB1234)</li>
                        <li>O ruta (ej: MAD-JFK)</li>
                        <li>O aerolínea + fecha</li>
                    </ul>
                </div>
                <div class="hp-step">
                    <span class="num">2</span>
                    <h5>Interpretar el mapa</h5>
                    <p>Claves del mapa:</p>
                    <ul class="hp-key">
                        <li><span class="dot dot--ok"></span>Vuelos en horario</li>
                        <li><span class="dot dot--late"></span>Vuelos retrasados</li>
                        <li><span class="dot dot--off"></span>Vuelos cancelados</li>
                        <li><i class="fas fa-plane"></i>Icono = dirección del avión</li>
                    </ul>
                </div>
            </div>

            <h4>Funciones avanzadas</h4>
            <div class="hp-steps hp-steps--3">
                <div class="hp-step">
                    <h5><i class="fas fa-filter"></i>Filtros</h5>
                    <p>Filtra por aerolínea, tipo de avión, altitud o país.</p>
                </div>
                <div class="hp-step">
                    <h5><i class="far fa-bell"></i>Alertas</h5>
                    <p>Recibe notificaciones cuando un vuelo despegue, aterrice o sufra retrasos.</p>
                </div>
                <div class="hp-step">
                    <h5><i class="fas fa-database"></i>Datos históricos</h5>
                    <p>Consulta estadísticas de puntualidad por aerolínea o ruta.</p>
                </div>
            </div>

            <div class="hp-source"><i class="fas fa-circle-info"></i>
                <span><strong>Fuente de datos:</strong> Nuestro Flight Tracker utiliza datos de <strong>ADS-B</strong>
                    combinados con información de aerolíneas y aeropuertos para máxima precisión.</span>
            </div>
        </section>

        {{-- Contacto --}}
        <section class="hp-contact">
            <div class="hp-contact-head">
                <small>Atención especializada</small>
                <h2>¿Necesitas ayuda personalizada?</h2>
                <p>Nuestros expertos en vuelos pueden ayudarte a encontrar la mejor opción para tu próximo viaje.</p>
            </div>
            <div class="hp-contact-grid">
                <div class="hp-contact-card ct-card">
                    <span class="ico"><i class="far fa-comments"></i></span>
                    <h3>Chat en vivo</h3>
                    <p>Conversa con nuestro equipo para resolver dudas de búsqueda o de la interfaz.</p>
                    <button type="button" class="hp-contact-btn is-primary" onclick="openChat()"><i class="far fa-comments"></i>Iniciar chat</button>
                </div>
                <div class="hp-contact-card ct-card">
                    <span class="ico"><i class="fas fa-phone"></i></span>
                    <h3>Línea telefónica</h3>
                    <p>Atención telefónica para consultas sobre tarifas y reservas.</p>
                    <strong class="mono">+34 900 123 456</strong>
                    <a href="tel:+34900123456" class="hp-contact-btn"><i class="fas fa-phone"></i>Llamar ahora</a>
                </div>
                <div class="hp-contact-card ct-card">
                    <span class="ico"><i class="far fa-envelope"></i></span>
                    <h3>Soporte por email</h3>
                    <p>Escríbenos tus sugerencias o cualquier incidencia que encuentres.</p>
                    <strong class="mono">ayuda@flylow.com</strong>
                    <a href="mailto:ayuda@flylow.com" class="hp-contact-btn"><i class="far fa-paper-plane"></i>Enviar email</a>
                </div>
            </div>
        </section>

        <div class="ct-project">
            <i class="fas fa-circle-info"></i>
            <div>
                <h2>Nota importante sobre este proyecto</h2>
                <p>Esta aplicación forma parte de un Proyecto de Fin de Ciclo (PFC). El contenido combina
                    <strong>funcionalidades reales implementadas</strong>, <strong>características planificadas para
                        desarrollo futuro</strong> y <strong>elementos ficticios</strong> con fines demostrativos.
                    Algunos de los datos mostrados son simulados y no representan servicios reales.</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('partials.tawk')
    <script src="{{ asset('js/help.js') }}?v={{ filemtime(public_path('js/help.js')) }}"></script>
@endsection
