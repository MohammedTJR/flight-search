@extends('plantilla')

@section('titulo_pagina', 'Términos y Condiciones | FlyLow - Comparador de vuelos')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/terms.css') }}">
@endsection

@section('contenido')
<div class="terms-page bg-light">
    <!-- Hero Section -->
    <div class="terms-hero py-5 bg-primary text-white">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Inicio</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Términos y Condiciones</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-3">Términos y Condiciones</h1>
                    <p class="lead mb-4">Última actualización: {{ now()->format('d/m/Y') }}</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#definiciones" class="btn btn-outline-light">Definiciones</a>
                        <a href="#uso-servicio" class="btn btn-outline-light">Uso del Servicio</a>
                        <a href="#limitacion" class="btn btn-outline-light">Responsabilidades</a>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0 text-center">
                    <img src="{{ asset('img/terms-document.png') }}" alt="Documento legal" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="terms-content my-5">
                    <!-- Introducción -->
                    <section class="mb-5">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Importante:</strong> Al utilizar FlyLow, aceptas estos Términos y Condiciones en su totalidad. Si no estás de acuerdo, no utilices nuestro servicio.
                        </div>
                        <p>Los presentes Términos y Condiciones regulan el uso del sitio web <strong>FlyLow</strong> (en adelante, "el Servicio"), operado por <strong>MOTJR</strong> (en adelante, "nosotros", "nuestro" o "la Empresa").</p>
                    </section>

                    <!-- 1. Definiciones -->
                    <section id="definiciones" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">1</span>
                            Definiciones
                        </h2>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-md-3">Servicio</dt>
                                    <dd class="col-md-9">Plataforma digital de comparación de vuelos accesible via web y aplicaciones móviles.</dd>

                                    <dt class="col-md-3">Usuario</dt>
                                    <dd class="col-md-9">Persona física o jurídica que accede o utiliza el Servicio.</dd>

                                    <dt class="col-md-3">Contenido</dt>
                                    <dd class="col-md-9">Datos, información, precios, textos, gráficos y demás elementos disponibles en el Servicio.</dd>

                                    <dt class="col-md-3">Socio Proveedor</dt>
                                    <dd class="col-md-9">Aerolíneas, agencias de viajes y otros proveedores de servicios de viaje cuyos productos se muestran en el Servicio.</dd>

                                    <dt class="col-md-3">Tarifa</dt>
                                    <dd class="col-md-9">Precio total mostrado para un vuelo, incluyendo tasas y cargos aplicables.</dd>
                                </dl>
                            </div>
                        </div>
                    </section>

                    <!-- 2. Uso del Servicio -->
                    <section id="uso-servicio" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">2</span>
                            Uso del Servicio
                        </h2>
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0">
                                <h3 class="h5 fw-bold mb-0"><i class="fas fa-check-circle text-success me-2"></i> Requisitos</h3>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li>Debes tener al menos <strong>18 años</strong> o contar con consentimiento parental.</li>
                                    <li>Proporcionar información <strong>veraz y actualizada</strong>.</li>
                                    <li>No utilizar el Servicio para fines <strong>ilegales o no autorizados</strong>.</li>
                                    <li>Aceptar nuestra <a href="{{ route('privacy') }}">Política de Privacidad</a>.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h3 class="h5 fw-bold mb-0"><i class="fas fa-ban text-danger me-2"></i> Prohibiciones</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Uso comercial no autorizado (scraping, spiders, etc.)</li>
                                            <li>Manipulación de precios o disponibilidad</li>
                                            <li>Suplantación de identidad</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Actividades fraudulentas</li>
                                            <li>Interferencia con la seguridad del Servicio</li>
                                            <li>Violación de derechos de propiedad intelectual</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="fas fa-exclamation-circle me-2"></i> El incumplimiento puede resultar en la <strong>terminación inmediata</strong> de tu acceso al Servicio sin reembolso.
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 3. Reservas -->
                    <section id="reservas" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">3</span>
                            Reservas y Pagos
                        </h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">3.1 Proceso de Reserva</h4>
                                <p>FlyLow actúa como <strong>mero intermediario</strong> entre tú y los Socios Proveedores. El contrato de transporte se celebra directamente con la aerolínea.</p>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> <strong>Importante:</strong> No vendemos billetes de avión ni somos una agencia de viajes. Somos un comparador independiente.
                                </div>

                                <h4 class="fw-bold mt-4 mb-3">3.2 Confirmación</h4>
                                <p>La reserva solo se considera confirmada cuando:</p>
                                <ol>
                                    <li>Recibes el <strong>email de confirmación</strong> de la aerolínea/agencia</li>
                                    <li>Se completa el pago satisfactoriamente</li>
                                    <li>Obtienes un <strong>localizador válido</strong></li>
                                </ol>

                                <h4 class="fw-bold mt-4 mb-3">3.3 Cancelaciones y Cambios</h4>
                                <p>Las políticas dependen <strong>exclusivamente</strong> de cada aerolínea:</p>
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo de Tarifa</th>
                                            <th>Cambios</th>
                                            <th>Cancelación</th>
                                            <th>Reembolso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Básica</td>
                                            <td>No permitidos</td>
                                            <td>Con penalización</td>
                                            <td>No reembolsable</td>
                                        </tr>
                                        <tr>
                                            <td>Flexible</td>
                                            <td>Permitidos (cargo)</td>
                                            <td>Permitida</td>
                                            <td>Parcial (según política)</td>
                                        </tr>
                                        <tr>
                                            <td>Premium</td>
                                            <td>Sin costo</td>
                                            <td>Sin penalización</td>
                                            <td>Total (hasta 24h antes)</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="mt-3"><i class="fas fa-external-link-alt me-2"></i> Consulta siempre las <strong>Condiciones de Transporte</strong> específicas de tu aerolínea.</p>
                            </div>
                        </div>
                    </section>

                    <!-- 4. Precios -->
                    <section id="precios" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">4</span>
                            Política de Precios
                        </h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">4.1 Exactitud de Precios</h4>
                                <p>Los precios mostrados son <strong>estimaciones</strong> basadas en información recibida de aerolíneas y partners. FlyLow:</p>
                                <ul>
                                    <li>No garantiza la exactitud de los precios mostrados</li>
                                    <li>No se responsabiliza por errores u omisiones</li>
                                    <li>Puede actualizar precios en cualquier momento</li>
                                </ul>

                                <h4 class="fw-bold mt-4 mb-3">4.2 Componentes del Precio</h4>
                                <p>El precio total incluye (cuando aplica):</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Tarifa base</li>
                                            <li>Tasas aeroportuarias</li>
                                            <li>Impuestos gubernamentales</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Cargos por combustible</li>
                                            <li>Comisiones de servicio</li>
                                            <li>Equipaje de mano (según política)</li>
                                        </ul>
                                    </div>
                                </div>

                                <h4 class="fw-bold mt-4 mb-3">4.3 Factores que Afectan el Precio</h4>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light h-100">
                                            <h5 class="fw-bold"><i class="fas fa-calendar-alt text-primary me-2"></i> Temporalidad</h5>
                                            <p class="mb-0">Precios varían por temporada alta/baja, días de la semana y hora del día.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light h-100">
                                            <h5 class="fw-bold"><i class="fas fa-users text-info me-2"></i> Demanda</h5>
                                            <p class="mb-0">Los precios aumentan cuando quedan pocos asientos disponibles.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light h-100">
                                            <h5 class="fw-bold"><i class="fas fa-cog text-warning me-2"></i> Configuración</h5>
                                            <p class="mb-0">Opciones como equipaje, asientos y seguros afectan el precio final.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 5. Propiedad Intelectual -->
                    <section id="propiedad" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">5</span>
                            Propiedad Intelectual
                        </h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">5.1 Derechos de FlyLow</h4>
                                <p>Todos los elementos del Servicio son propiedad de FlyLow o se utilizan con licencia:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Logotipos y marca registrada</li>
                                            <li>Diseño y maquetación (UI/UX)</li>
                                            <li>Algoritmos de comparación</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Contenido generado</li>
                                            <li>Bases de datos de vuelos</li>
                                            <li>Software y código fuente</li>
                                        </ul>
                                    </div>
                                </div>

                                <h4 class="fw-bold mt-4 mb-3">5.2 Licencia Limitada</h4>
                                <p>Concedemos una licencia <strong>no exclusiva, intransferible y revocable</strong> para:</p>
                                <ol>
                                    <li>Acceder y usar el Servicio personalmente</li>
                                    <li>Realizar búsquedas y comparaciones</li>
                                    <li>Hacer reservas a través de nuestros links</li>
                                </ol>
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Prohibido:</strong> Cualquier uso comercial no autorizado, incluyendo scraping, minería de datos o reproducción masiva de contenido.
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 6. Limitación de Responsabilidad -->
                    <section id="limitacion" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">6</span>
                            Limitación de Responsabilidad
                        </h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">6.1 Alcance del Servicio</h4>
                                <p>FlyLow es un <strong>comparador independiente</strong> y no participa en:</p>
                                <ul>
                                    <li>Contratos de transporte aéreo</li>
                                    <li>Gestión de reservas o pagos</li>
                                    <li>Servicio al cliente post-venta</li>
                                </ul>

                                <h4 class="fw-bold mt-4 mb-3">6.2 Exclusiones</h4>
                                <p>No nos hacemos responsables por:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Errores en precios o disponibilidad</li>
                                            <li>Cancelaciones o cambios de vuelos</li>
                                            <li>Problemas con aerolíneas o aeropuertos</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Retrasos o pérdidas de equipaje</li>
                                            <li>Daños por uso incorrecto del Servicio</li>
                                            <li>Eventos de fuerza mayor</li>
                                        </ul>
                                    </div>
                                </div>

                                <h4 class="fw-bold mt-4 mb-3">6.3 Responsabilidad Máxima</h4>
                                <p>En caso de responsabilidad demostrada, nuestra obligación se limitará al <strong>menor</strong> de:</p>
                                <ol>
                                    <li>El importe total pagado por el Usuario en los últimos 6 meses</li>
                                    <li>500 euros (€)</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- 7. Modificaciones -->
                    <section id="modificaciones" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">7</span>
                            Modificaciones
                        </h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">7.1 Cambios en los Términos</h4>
                                <p>Nos reservamos el derecho de modificar estos Términos en cualquier momento:</p>
                                <ul>
                                    <li>Los cambios entrarán en vigor al publicarse</li>
                                    <li>Notificaremos cambios sustanciales por email</li>
                                    <li>El uso continuado constituye aceptación</li>
                                </ul>

                                <h4 class="fw-bold mt-4 mb-3">7.2 Historial de Versiones</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Fecha</th>
                                                <th>Cambios Relevantes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2.1</td>
                                                <td>{{ now()->subDays(15)->format('d/m/Y') }}</td>
                                                <td>Actualización política de cancelaciones</td>
                                            </tr>
                                            <tr>
                                                <td>2.0</td>
                                                <td>{{ now()->subMonths(3)->format('d/m/Y') }}</td>
                                                <td>Nueva sección de responsabilidad</td>
                                            </tr>
                                            <tr>
                                                <td>1.5</td>
                                                <td>{{ now()->subYear()->format('d/m/Y') }}</td>
                                                <td>Primera versión publicada</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 8. Ley Aplicable -->
                    <section id="ley-aplicable" class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">8</span>
                            Disposiciones Finales
                        </h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">8.1 Legislación Aplicable</h4>
                                <p>Estos Términos se rigen por:</p>
                                <ul>
                                    <li>Ley española (para usuarios en España)</li>
                                    <li>Reglamento (UE) 2019/1150 sobre comparadores</li>
                                    <li>Directiva (UE) 2015/2302 sobre viajes combinados</li>
                                </ul>

                                <h4 class="fw-bold mt-4 mb-3">8.2 Resolución de Conflictos</h4>
                                <p>En caso de disputa:</p>
                                <ol>
                                    <li>Contacta con nuestro servicio al cliente</li>
                                    <li>Plataforma de Resolución de Litigios en Línea de la UE (<a href="https://ec.europa.eu/consumers/odr" target="_blank">ODR</a>)</li>
                                    <li>Tribunales de Madrid (España)</li>
                                </ol>

                                <h4 class="fw-bold mt-4 mb-3">8.3 Contacto</h4>
                                <p>Para cuestiones legales:</p>
                                <address>
                                    <strong>MOTJR</strong><br>
                                    Gandia, Valencia<br>
                                    <i class="fas fa-envelope me-2"></i> info@flylow.com<br>
                                    <i class="fas fa-phone-alt me-2"></i> +34 900 123 456
                                </address>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
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
});
</script>
@endsection