@extends('plantilla')

@section('titulo_pagina', 'Centro de Ayuda | FlyLow - Comparador de vuelos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
@endsection

@section('contenido')
    <div class="help-container py-5">
        <div class="container">
            <!-- Hero Section -->
            <div class="help-hero text-center mb-5">
                <h1 class="display-4 fw-bold mb-3">Centro de Ayuda FlyLow</h1>
                <p class="lead">Aprende a encontrar las mejores ofertas de vuelos y aprovecha al máximo nuestro comparador
                </p>

                <!-- Search Bar -->
                <div class="search-help mt-4 mx-auto" style="max-width: 600px;">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" placeholder="¿Cómo podemos ayudarte?"
                            aria-label="Buscar ayuda">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search me-2"></i> Buscar
                        </button>
                    </div>
                    <div class="popular-searches mt-2">
                        <span class="text-muted">Búsquedas populares: </span>
                        <a href="#buscar-vuelos" class="badge bg-light text-dark me-1">Buscar vuelos</a>
                        <a href="#favoritos" class="badge bg-light text-dark me-1">Favoritos</a>
                        <a href="#tracker" class="badge bg-light text-dark me-1">Flight Tracker</a>
                    </div>
                </div>
            </div>

            <!-- Help Categories -->
            <div class="help-categories mb-5">
                <div class="row g-4">
                    <!-- Categoría 1 -->
                    <div class="col-md-4">
                        <div class="card category-card h-100 border-0 shadow-sm hover-effect">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-primary-light mb-3 mx-auto rounded-circle">
                                    <i class="fas fa-search-dollar text-primary fs-3"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-3">Buscar vuelos</h3>
                                <p class="text-muted mb-0">Aprende a encontrar las mejores ofertas y comparar precios</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center pb-4">
                                <a href="#buscar-vuelos" class="btn btn-outline-primary">Ver guía</a>
                            </div>
                        </div>
                    </div>

                    <!-- Categoría 2 -->
                    <div class="col-md-4">
                        <div class="card category-card h-100 border-0 shadow-sm hover-effect">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-success-light mb-3 mx-auto rounded-circle">
                                    <i class="fas fa-heart text-success fs-3"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-3">Favoritos</h3>
                                <p class="text-muted mb-0">Guarda tus vuelos favoritos y recibe alertas de precio</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center pb-4">
                                <a href="#favoritos" class="btn btn-outline-success">Ver guía</a>
                            </div>
                        </div>
                    </div>

                    <!-- Categoría 3 -->
                    <div class="col-md-4">
                        <div class="card category-card h-100 border-0 shadow-sm hover-effect">
                            <div class="card-body text-center p-4">
                                <div class="icon-wrapper bg-info-light mb-3 mx-auto rounded-circle">
                                    <i class="fas fa-plane-departure text-info fs-3"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-3">Flight Tracker</h3>
                                <p class="text-muted mb-0">Sigue precios en tiempo real y recibe alertas</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center pb-4">
                                <a href="#tracker" class="btn btn-outline-info">Ver guía</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section mb-5">
                <h2 class="fw-bold mb-4">Guías de uso</h2>

                <!-- Sección Buscar Vuelos -->
                <div class="guide-section mb-5" id="buscar-vuelos">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-wrapper bg-primary-light me-3 rounded-circle">
                            <i class="fas fa-search-dollar text-primary fs-4"></i>
                        </div>
                        <h3 class="mb-0">Cómo buscar y comparar vuelos</h3>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold mb-3">1. Búsqueda básica</h4>
                            <p>Sigue estos pasos para encontrar vuelos:</p>
                            <ol>
                                <li>Ingresa tus ciudades de origen y destino</li>
                                <li>Selecciona fechas (o marca "Fechas flexibles")</li>
                                <li>Especifica número de pasajeros</li>
                                <li>Haz clic en "Buscar vuelos"</li>
                            </ol>

                            <div class="alert alert-primary mt-3">
                                <i class="fas fa-lightbulb me-2"></i> Usa nuestro <strong>calendario de precios</strong>
                                para ver los días más económicos para volar.
                            </div>

                            <h4 class="fw-bold mt-4 mb-3">2. Filtros avanzados</h4>
                            <p>Puedes refinar tus resultados con:</p>
                            <ul>
                                <li><strong>Aerolíneas:</strong> Compara precios entre diferentes compañías</li>
                                <li><strong>Escalas:</strong> Filtra por vuelos directos o con escalas</li>
                                <li><strong>Horarios:</strong> Busca por franjas horarias específicas</li>
                                <li><strong>Precio:</strong> Establece un rango de precios máximo/mínimo</li>
                            </ul>

                            <div class="row mt-4 g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h5 class="fw-bold"><i class="fas fa-check-circle text-success me-2"></i> Consejo
                                            profesional</h5>
                                        <p class="mb-0">Busca en ventanas de incógnito para evitar precios inflados por
                                            cookies de seguimiento.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h5 class="fw-bold"><i class="fas fa-bell text-warning me-2"></i> ¿Sabías que?</h5>
                                        <p class="mb-0">Los martes por la tarde suelen ser el mejor momento para encontrar
                                            ofertas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Favoritos -->
                <div class="guide-section mb-5" id="favoritos">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-wrapper bg-success-light me-3 rounded-circle">
                            <i class="fas fa-heart text-success fs-4"></i>
                        </div>
                        <h3 class="mb-0">Gestión de vuelos favoritos</h3>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold mb-3">Guardar vuelos</h4>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <p>Para guardar un vuelo en tus favoritos:</p>
                                    <ol>
                                        <li>Busca vuelos como normalmente lo harías</li>
                                        <li>En los resultados, haz clic en el icono <i class="far fa-heart text-danger"></i>
                                            junto al vuelo que te interese</li>
                                        <li>Accede a tu sección de <a href="{{ route('favorites.show') }}"
                                                class="text-primary">Favoritos</a> para ver todos tus vuelos guardados</li>
                                    </ol>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="{{ asset('img/favoritos-demo.png') }}" alt="Guardar favoritos"
                                        class="img-fluid rounded border">
                                </div>
                            </div>

                            <h4 class="fw-bold mt-4 mb-3">Alertas de precio</h4>
                            <p>Configura alertas para recibir notificaciones cuando bajen los precios:</p>
                            <ul>
                                <li>En tu lista de favoritos, activa "Notificaciones" para cada vuelo</li>
                                <li>Establece tu precio deseado</li>
                                <li>Recibirás un email cuando el precio baje a tu nivel deseado</li>
                            </ul>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i> Las alertas de precio también están disponibles
                                directamente desde los resultados de búsqueda.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Flight Tracker -->
                <!-- Sección Flight Tracker (Seguimiento de aviones en tiempo real) -->
                <div class="guide-section mb-5" id="tracker">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-wrapper bg-info-light me-3 rounded-circle">
                            <i class="fas fa-plane-departure text-info fs-4"></i>
                        </div>
                        <h3 class="mb-0">Flight Tracker: Seguimiento de aviones en tiempo real</h3>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold mb-3">¿Qué es nuestro Flight Tracker?</h4>
                            <p>Una herramienta avanzada que te permite:</p>
                            <ul>
                                <li><strong>Seguir aviones en vuelo</strong> en tiempo real en un mapa interactivo</li>
                                <li>Ver <strong>datos de vuelo</strong>: altitud, velocidad, ruta y retrasos</li>
                                <li>Consultar <strong>información histórica</strong> de rutas y aerolíneas</li>
                                <li>Recibir <strong>alertas</strong> sobre cambios en vuelos específicos</li>
                            </ul>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h4 class="fw-bold mb-3">Cómo usar el Flight Tracker</h4>
                                    <div class="mb-4">
                                        <h5 class="fw-bold"><span class="badge bg-primary me-2">1</span> Buscar un vuelo
                                        </h5>
                                        <p>Ingresa:</p>
                                        <ul>
                                            <li>Número de vuelo (ej: IB1234)</li>
                                            <li>O ruta (ej: MAD-JFK)</li>
                                            <li>O aerolínea + fecha</li>
                                        </ul>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="fw-bold"><span class="badge bg-primary me-2">2</span> Interpretar el mapa
                                        </h5>
                                        <p>Claves del mapa:</p>
                                        <ul>
                                            <li><span class="badge bg-success me-1">■</span> Vuelos en horario</li>
                                            <li><span class="badge bg-warning me-1">■</span> Vuelos retrasados</li>
                                            <li><span class="badge bg-danger me-1">■</span> Vuelos cancelados</li>
                                            <li><i class="fas fa-plane me-1 text-primary"></i> Icono = Dirección del avión
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded text-center">
                                        <img src="{{ asset('img/flight-radar-demo.png') }}" alt="Flight Tracker Demo"
                                            class="img-fluid rounded border mb-2">
                                        <p class="small text-muted mb-0">Vista del Flight Tracker mostrando aviones en
                                            tiempo real sobre Europa</p>
                                    </div>
                                </div>
                            </div>

                            <h4 class="fw-bold mt-4 mb-3">Funciones avanzadas</h4>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded h-100">
                                        <h5 class="fw-bold"><i class="fas fa-filter text-primary me-2"></i> Filtros</h5>
                                        <p class="mb-0">Filtra por aerolínea, tipo de avión, altitud o país.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded h-100">
                                        <h5 class="fw-bold"><i class="fas fa-bell text-warning me-2"></i> Alertas</h5>
                                        <p class="mb-0">Recibe notificaciones cuando un vuelo despegue, aterrice o sufra
                                            retrasos.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded h-100">
                                        <h5 class="fw-bold"><i class="fas fa-database text-info me-2"></i> Datos históricos
                                        </h5>
                                        <p class="mb-0">Consulta estadísticas de puntualidad por aerolínea o ruta.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-2"></i> <strong>Fuente de datos:</strong> Nuestro Flight
                                Tracker utiliza datos de <strong>ADS-B</strong> combinados con información de aerolíneas y
                                aeropuertos para máxima precisión.
                            </div>
                        </div>
                    </div>
                </div>

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
            </div>




            <!-- Contact Help Section -->
            <div class="contact-help bg-light p-5 rounded-3 mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-3">¿Necesitas ayuda personalizada?</h2>
                        <p class="lead mb-4">Nuestros expertos en vuelos pueden ayudarte a encontrar la mejor opción para tu
                            próximo viaje.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="javascript:void(Tawk_API.toggle())" class="btn btn-primary px-4">
                                <i class="fas fa-comments me-2"></i> Chat en vivo
                            </a>
                            <a href="mailto:ayuda@flylow.com" class="btn btn-outline-dark px-4">
                                <i class="fas fa-envelope me-2"></i> Enviar email
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <img src="{{ asset('img/help-expert.png') }}" alt="Expertos en vuelos" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Smooth scrolling para anclas
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Configuración de Tawk.to con autenticación automática
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();

        @auth
            Tawk_API.onLoad = function() {
                // Configurar datos del usuario autenticado
                Tawk_API.setAttributes({
                    'name': '{{ Auth::user()->name }}',
                    'email': '{{ Auth::user()->email }}',
                    'hash': '{{ hash_hmac("sha256", Auth::user()->email, "tu_clave_secreta") }}'
                }, function (error) { });
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
    </script>
@endsection