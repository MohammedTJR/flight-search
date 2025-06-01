@extends('plantilla')

@section('titulo_pagina', 'Política de Privacidad | FlyLow')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/privacy.css') }}">
@endsection

@section('contenido')
<div class="privacy-page bg-light">
    <!-- Hero Section -->
    <div class="privacy-hero py-5 bg-dark text-white">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Inicio</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Política de Privacidad</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-3">Política de Privacidad</h1>
                    <p class="lead mb-4">Última actualización: {{ now()->format('d/m/Y') }}</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#resumen" class="btn btn-outline-light">Resumen Ejecutivo</a>
                        <a href="#control-datos" class="btn btn-outline-light">Control de tus datos</a>
                        <a href="#cookies" class="btn btn-outline-light">Uso de Cookies</a>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0 text-center">
                    <img src="{{ asset('img/privacy-shield.png') }}" alt="Protección de datos" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Summary -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm my-5" id="resumen">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="fas fa-file-contract fa-lg text-primary"></i>
                            </div>
                            <h2 class="mb-0">Resumen Ejecutivo</h2>
                        </div>
                        
                        <div class="alert alert-primary">
                            <i class="fas fa-info-circle me-2"></i> Esta sección resume nuestra política completa en <strong>términos sencillos</strong>. Para detalles legales, consulta las secciones correspondientes.
                        </div>
                        
                        <div class="row g-4 mt-3">
                            <div class="col-md-6">
                                <div class="p-4 border rounded h-100">
                                    <h5 class="fw-bold"><i class="fas fa-database text-primary me-2"></i> Qué recopilamos</h5>
                                    <ul class="mb-0">
                                        <li>Datos de búsqueda (origen, destino, fechas)</li>
                                        <li>Información de cuenta (email, nombre)</li>
                                        <li>Comportamiento en el sitio (anónimo)</li>
                                        <li>Datos de pago (procesados externamente)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded h-100">
                                    <h5 class="fw-bold"><i class="fas fa-shield-alt text-success me-2"></i> Cómo protegemos</h5>
                                    <ul class="mb-0">
                                        <li>Encriptación SSL/TLS</li>
                                        <li>Protección contra breaches</li>
                                        <li>Accesos restringidos</li>
                                        <li>Auditorías periódicas</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded h-100">
                                    <h5 class="fw-bold"><i class="fas fa-share-square text-info me-2"></i> Compartir datos</h5>
                                    <ul class="mb-0">
                                        <li>Aerolíneas para reservas</li>
                                        <li>Proveedores de pago</li>
                                        <li>Análisis de tráfico (Google Analytics)</li>
                                        <li>Solo cuando es necesario</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded h-100">
                                    <h5 class="fw-bold"><i class="fas fa-user-cog text-warning me-2"></i> Tus derechos</h5>
                                    <ul class="mb-0">
                                        <li>Acceso a tus datos</li>
                                        <li>Solicitar corrección</li>
                                        <li>Pedir eliminación</li>
                                        <li>Oponerte al procesamiento</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Full Policy Content -->
                <div class="privacy-content mb-5">
                    <!-- 1. Introducción -->
                    <section class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">1</span>
                            Introducción
                        </h2>
                        <p>En <strong>FlyLow</strong> (operado por MOTJR), protegemos tu privacidad conforme al <strong>RGPD (UE) 2016/679</strong> y <strong>LOPDGDD 3/2018</strong>. Esta política explica:</p>
                        <ul>
                            <li>Qué información recopilamos y por qué</li>
                            <li>Cómo utilizamos y compartimos tu información</li>
                            <li>Tus derechos y opciones</li>
                        </ul>
                        <p class="mb-0">Al usar nuestro servicio, aceptas esta política. <strong>No vendemos tus datos personales</strong>.</p>
                    </section>
                    
                    <!-- 2. Datos recopilados -->
                    <section class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">2</span>
                            Datos que Recopilamos
                        </h2>
                        
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
                    <section class="mb-5">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">3</span>
                            Finalidad del Tratamiento
                        </h2>
                        
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
                    <section class="mb-5" id="control-datos">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">4</span>
                            Control de Tus Datos
                        </h2>
                        
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
                    <section class="mb-5" id="cookies">
                        <h2 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="section-number me-3">5</span>
                            Política de Cookies
                        </h2>
                        
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
                </div>
                
                <!-- Final CTA -->
                <div class="card border-0 shadow-sm bg-white mb-5">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold mb-3">¿Tienes preguntas sobre privacidad?</h3>
                        <p class="lead mb-4">Nuestro Delegado de Protección de Datos está disponible para resolver tus dudas.</p>
                        <a href="mailto:info@flylow.com" class="btn btn-primary px-4 me-2">
                            <i class="fas fa-envelope me-2"></i> Contactar DPO
                        </a>
                        <a href="{{ route('help') }}" class="btn btn-outline-primary px-4">
                            <i class="fas fa-phone me-2"></i> Llamar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll para anclas
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