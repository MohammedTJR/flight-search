@extends('plantilla')

@section('titulo_pagina', 'Política de Privacidad | FlyLow')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ filemtime(public_path('css/content.css')) }}">
@endsection

@section('contenido')
<section class="ct-hero">
    <div class="ct-wrap">
        <ol class="ct-crumbs">
            <li><a href="{{ route('home') }}">Inicio</a></li>
            <li>Política de Privacidad</li>
        </ol>
        <span class="ct-chip"><i class="fas fa-shield-halved"></i>Conforme al RGPD (UE) 2016/679 y LOPDGDD 3/2018</span>
        <h1 class="ct-title">Política de Privacidad</h1>
        <p class="ct-lead">Última actualización: {{ now()->format('d/m/Y') }}. En FlyLow protegemos tu privacidad y <strong>no vendemos tus datos personales</strong>.</p>
        <div class="ct-actions">
            <a href="#cookies" class="ct-btn ct-btn--primary"><i class="fas fa-cookie-bite"></i>Uso de cookies</a>
            <a href="#control-datos" class="ct-btn"><i class="fas fa-user-shield"></i>Control de tus datos</a>
            <button type="button" class="ct-btn ct-btn--soft" onclick="window.print()"><i class="fas fa-print"></i>Imprimir</button>
        </div>

        <div class="ct-summary" id="resumen">
            <article class="ct-summary-card">
                <span class="ico"><i class="fas fa-database"></i></span>
                <small>01 / Recopilación</small>
                <h2>Qué recopilamos</h2>
                <ul>
                    <li>Datos de búsqueda (origen, destino, fechas)</li>
                    <li>Información de cuenta (email, nombre)</li>
                    <li>Comportamiento en el sitio (anónimo)</li>
                    <li>Datos de pago (procesados externamente)</li>
                </ul>
            </article>
            <article class="ct-summary-card">
                <span class="ico"><i class="fas fa-lock"></i></span>
                <small>02 / Protección</small>
                <h2>Cómo protegemos</h2>
                <ul>
                    <li>Encriptación SSL/TLS</li>
                    <li>Protección contra breaches</li>
                    <li>Accesos restringidos</li>
                    <li>Auditorías periódicas</li>
                </ul>
            </article>
            <article class="ct-summary-card">
                <span class="ico"><i class="fas fa-share-nodes"></i></span>
                <small>03 / Transferencias</small>
                <h2>Compartir datos</h2>
                <ul>
                    <li>Aerolíneas para reservas</li>
                    <li>Proveedores de pago</li>
                    <li>Análisis de tráfico (Google Analytics)</li>
                    <li>Solo cuando es necesario</li>
                </ul>
            </article>
            <article class="ct-summary-card">
                <span class="ico"><i class="fas fa-user-gear"></i></span>
                <small>04 / Control</small>
                <h2>Tus derechos</h2>
                <ul>
                    <li>Acceso a tus datos</li>
                    <li>Solicitar corrección</li>
                    <li>Pedir eliminación</li>
                    <li>Oponerte al procesamiento</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<div class="ct-wrap ct-body">
    <div class="ct-layout">
        <aside class="ct-side">
            <nav class="ct-card ct-toc" aria-label="Índice del documento">
                <div class="ct-toc-head">Índice del documento <b>5 secciones</b></div>
                <ol>
                    <li><a href="#intro"><span class="n">1</span>Introducción</a></li>
                    <li><a href="#datos"><span class="n">2</span>Datos que recopilamos</a></li>
                    <li><a href="#finalidad"><span class="n">3</span>Finalidad del tratamiento</a></li>
                    <li><a href="#control-datos"><span class="n">4</span>Control de tus datos</a></li>
                    <li><a href="#cookies"><span class="n">5</span>Política de cookies</a></li>
                </ol>
            </nav>
        </aside>

        <main class="lg-content">
                    <!-- 1. Introducción -->
                    <section class="lg-section" id="intro">
                        <h2 class="lg-head"><span>1</span>Introducción</h2>
                        <p>En <strong>FlyLow</strong> (operado por MOTJR), protegemos tu privacidad conforme al <strong>RGPD (UE) 2016/679</strong> y <strong>LOPDGDD 3/2018</strong>. Esta política explica:</p>
                        <ul>
                            <li>Qué información recopilamos y por qué</li>
                            <li>Cómo utilizamos y compartimos tu información</li>
                            <li>Tus derechos y opciones</li>
                        </ul>
                        <p class="mb-0">Al usar nuestro servicio, aceptas esta política. <strong>No vendemos tus datos personales</strong>.</p>
                    </section>
                    
                    <!-- 2. Datos recopilados -->
                    <section class="lg-section" id="datos">
                        <h2 class="lg-head"><span>2</span>Datos que Recopilamos</h2>
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0">
                                <h3 class="h5 fw-bold mb-0"><i class="fas fa-user me-2"></i> Datos personales</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:30%">Categoría</th>
                                            <th>Ejemplos</th>
                                            <th style="width:20%">Cuándo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Identificación</td>
                                            <td>Nombre, email, dirección IP</td>
                                            <td>Registro, reservas</td>
                                        </tr>
                                        <tr>
                                            <td>Transacciones</td>
                                            <td>Búsquedas, historial de reservas</td>
                                            <td>Uso del servicio</td>
                                        </tr>
                                        <tr>
                                            <td>Técnicos</td>
                                            <td>Dispositivo, navegador, ubicación aproximada</td>
                                            <td>Siempre</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="mb-0"><i class="fas fa-info-circle text-info me-2"></i> Los datos de pago son procesados directamente por <strong>Stripe</strong> y <strong>PayPal</strong> sin almacenarlos en nuestros servidores.</p>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h3 class="h5 fw-bold mb-0"><i class="fas fa-cookie me-2"></i> Datos automáticos</h3>
                            </div>
                            <div class="card-body">
                                <p>Recopilamos mediante cookies y tecnologías similares:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li><strong>Cookies esenciales:</strong> Inicio de sesión, funcionalidad básica</li>
                                            <li><strong>Analíticas:</strong> Google Analytics (anonimizado)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li><strong>Publicitarias:</strong> Remarketing (solo si das consentimiento)</li>
                                            <li><strong>Preferencias:</strong> Configuración de usuario</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="alert alert-light mt-3">
                                    <i class="fas fa-tablet-alt me-2"></i> <strong>Dispositivos móviles:</strong> Recopilamos datos de geolocalización (solo con permiso) para mostrar ofertas relevantes.
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <!-- 3. Uso de datos -->
                    <section class="lg-section" id="finalidad">
                        <h2 class="lg-head"><span>3</span>Finalidad del Tratamiento</h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-4 border rounded h-100">
                                            <h5 class="fw-bold text-primary"><i class="fas fa-check-circle me-2"></i> Usos principales</h5>
                                            <ul>
                                                <li>Proveer y mejorar nuestro servicio</li>
                                                <li>Procesar reservas y pagos</li>
                                                <li>Personalizar recomendaciones</li>
                                                <li>Prevenir fraudes</li>
                                                <li>Cumplir obligaciones legales</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 border rounded h-100">
                                            <h5 class="fw-bold text-primary"><i class="fas fa-ban me-2"></i> Lo que no hacemos</h5>
                                            <ul>
                                                <li>Vender datos a terceros</li>
                                                <li>Compartir datos sin necesidad operativa</li>
                                                <li>Usar datos sensibles sin consentimiento explícito</li>
                                                <li>Retener datos más tiempo del necesario</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="fw-bold mt-5 mb-3">Bases legales según RGPD</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Actividad</th>
                                                <th>Base legal</th>
                                                <th>Periodo retención</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Reservas</td>
                                                <td>Ejecución de contrato</td>
                                                <td>5 años (obligación fiscal)</td>
                                            </tr>
                                            <tr>
                                                <td>Newsletters</td>
                                                <td>Consentimiento</td>
                                                <td>Hasta baja</td>
                                            </tr>
                                            <tr>
                                                <td>Análisis web</td>
                                                <td>Interés legítimo</td>
                                                <td>26 meses</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <!-- 4. Control de datos -->
                    <section class="lg-section" id="control-datos">
                        <h2 class="lg-head"><span>4</span>Control de Tus Datos</h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-4 border rounded h-100">
                                            <h5 class="fw-bold text-success"><i class="fas fa-user-edit me-2"></i> Tus derechos</h5>
                                            <p>Según el RGPD tienes derecho a:</p>
                                            <ul>
                                                <li><strong>Acceso:</strong> Solicitar copia de tus datos</li>
                                                <li><strong>Rectificación:</strong> Corregir información</li>
                                                <li><strong>Oposición:</strong> Limitar ciertos tratamientos</li>
                                                <li><strong>Portabilidad:</strong> Obtener tus datos en formato estructurado</li>
                                                <li><strong>Eliminación:</strong> "Derecho al olvido"</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 border rounded h-100">
                                            <h5 class="fw-bold text-success"><i class="fas fa-tools me-2"></i> Cómo ejercerlos</h5>
                                            <ol>
                                                <li>Inicia sesión en <strong>Mi cuenta → Privacidad</strong></li>
                                                <li>O envía solicitud a <a href="mailto:privacidad@flylow.com">privacidad@flylow.com</a></li>
                                                <li>Incluye copia de DNI/pasaporte para verificación</li>
                                            </ol>
                                            <p class="mb-0">Responderemos en <strong>máximo 30 días</strong>.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="fw-bold mt-5 mb-3">Configuración de privacidad</h4>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light h-100">
                                            <h5 class="fw-bold"><i class="fas fa-cookie me-2"></i> Cookies</h5>
                                            <p class="mb-0">Gestiona tus preferencias en cualquier momento:</p>
                                            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary mt-2">Abrir configuración</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light h-100">
                                            <h5 class="fw-bold"><i class="fas fa-bell me-2"></i> Notificaciones</h5>
                                            <p class="mb-0">Controla qué comunicaciones recibes:</p>
                                            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary mt-2">Gestionar</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light h-100">
                                            <h5 class="fw-bold"><i class="fas fa-user-shield me-2"></i> Seguridad</h5>
                                            <p class="mb-0">Activa la autenticación en dos pasos:</p>
                                            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary mt-2">Proteger cuenta</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <!-- 5. Cookies -->
                    <section class="lg-section" id="cookies">
                        <h2 class="lg-head"><span>5</span>Política de Cookies</h2>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <p>Usamos cookies para:</p>
                                <div class="row mb-4 g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 border-start border-4 border-primary bg-light rounded">
                                            <h5 class="fw-bold">Esenciales</h5>
                                            <p class="small mb-0">Funcionalidad básica (no desactivables)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 border-start border-4 border-info bg-light rounded">
                                            <h5 class="fw-bold">Rendimiento</h5>
                                            <p class="small mb-0">Mejorar experiencia (anonimizado)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 border-start border-4 border-warning bg-light rounded">
                                            <h5 class="fw-bold">Marketing</h5>
                                            <p class="small mb-0">Solo con consentimiento explícito</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="fw-bold mb-3">Cookies que utilizamos</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Proveedor</th>
                                                <th>Propósito</th>
                                                <th>Caducidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>flylow_session</td>
                                                <td>FlyLow</td>
                                                <td>Sesión de usuario</td>
                                                <td>2 horas</td>
                                            </tr>
                                            <tr>
                                                <td>_ga</td>
                                                <td>Google Analytics</td>
                                                <td>Distinción de usuarios</td>
                                                <td>2 años</td>
                                            </tr>
                                            <tr>
                                                <td>_gid</td>
                                                <td>Google Analytics</td>
                                                <td>Distinción de usuarios</td>
                                                <td>24 horas</td>
                                            </tr>
                                            <tr>
                                                <td>cookie_consent</td>
                                                <td>FlyLow</td>
                                                <td>Preferencias de cookies</td>
                                                <td>1 año</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-info-circle me-2"></i> Puedes gestionar tus preferencias en cualquier momento haciendo clic en <strong>"Configuración de Cookies"</strong> en el pie de página.
                                </div>
                            </div>
                        </div>
                    </section>

            <div class="ct-project">
                <i class="fas fa-circle-info"></i>
                <div>
                    <h2>Nota importante sobre este proyecto</h2>
                    <p>Esta aplicación forma parte de un Trabajo de Fin de Grado (TFG). El contenido combina
                        <strong>funcionalidades reales implementadas</strong>, <strong>características planificadas para
                            desarrollo futuro</strong> y <strong>elementos ficticios</strong> con fines demostrativos.
                        Algunos de los datos mostrados son simulados y no representan servicios reales.</p>
                </div>
            </div>

            <section class="ct-cta">
                <h2>¿Tienes preguntas sobre privacidad?</h2>
                <p>Nuestro Delegado de Protección de Datos está disponible para resolver tus dudas.</p>
                <div class="ct-cta-actions">
                    <a href="mailto:info@flylow.com" class="ct-btn ct-btn--primary"><i class="far fa-envelope"></i>Contactar DPO</a>
                    <a href="{{ route('help') }}" class="ct-btn"><i class="fas fa-phone"></i>Llamar</a>
                </div>
            </section>
        </main>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/toc.js') }}?v={{ filemtime(public_path('js/toc.js')) }}"></script>
@endsection
