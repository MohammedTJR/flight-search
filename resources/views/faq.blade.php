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
                
                <!-- Search Bar -->
                <div class="search-faq mx-auto" style="max-width: 600px;">
                    <div class="input-group shadow-lg">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-lg border-0" placeholder="Buscar en preguntas frecuentes..." id="faqSearch">
                        <button class="btn btn-primary px-4" type="button">Buscar</button>
                    </div>
                    <div class="popular-tags mt-3">
                        <a href="#comparacion" class="btn btn-sm btn-outline-light me-2">Comparación</a>
                        <a href="#favoritos" class="btn btn-sm btn-outline-light me-2">Favoritos</a>
                        <a href="#tracker" class="btn btn-sm btn-outline-light me-2">Flight Tracker</a>
                        <a href="#cuenta" class="btn btn-sm btn-outline-light">Cuenta</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <div class="faq-sidebar card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="fas fa-list-ul me-2"></i> Categorías</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="#comparacion">
                                    <i class="fas fa-exchange-alt me-2"></i> Comparación
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#favoritos">
                                    <i class="fas fa-heart me-2"></i> Favoritos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tracker">
                                    <i class="fas fa-plane me-2"></i> Flight Tracker
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#cuenta">
                                    <i class="fas fa-user me-2"></i> Cuenta
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#pagos">
                                    <i class="fas fa-credit-card me-2"></i> Pagos
                                </a>
                            </li>
                        </ul>
                        
                        <hr class="my-4">
                        
                        <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2"></i> ¿No encuentras?</h5>
                        <a href="{{ route('help') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-envelope me-2"></i> Contactar soporte
                        </a>
                        <a href="#" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#chatModal">
                            <i class="fas fa-comments me-2"></i> Chat en vivo
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Content -->
            <div class="col-lg-9">
                <!-- Comparación Section -->
                <section id="comparacion" class="mb-5 pb-4">
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
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComp1">
                                    ¿Cómo garantizan que encuentran los mejores precios?
                                </button>
                            </h3>
                            <div id="collapseComp1" class="accordion-collapse collapse" data-bs-parent="#accordionComparacion">
                                <div class="accordion-body">
                                    <p>Nuestra tecnología escanea en tiempo real:</p>
                                    <ul>
                                        <li><strong>+120 aerolíneas</strong> y <strong>50+ OTAs</strong> (Online Travel Agencies)</li>
                                        <li>Precios con/sin equipaje para comparación justa</li>
                                        <li>Tarifas flexibles y reembolsables</li>
                                    </ul>
                                    <div class="alert alert-light mt-3">
                                        <i class="fas fa-chart-line text-primary me-2"></i> Usamos IA para predecir tendencias y sugerir el mejor momento para comprar.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Item 2 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComp2">
                                    ¿Por qué veo precios diferentes al finalizar la reserva?
                                </button>
                            </h3>
                            <div id="collapseComp2" class="accordion-collapse collapse" data-bs-parent="#accordionComparacion">
                                <div class="accordion-body">
                                    <p>Esto puede ocurrir porque:</p>
                                    <ol>
                                        <li>Algunas aerolíneas actualizan precios en tiempo real</li>
                                        <li>Hay cargos adicionales (equipaje, asientos) no incluidos inicialmente</li>
                                        <li>La tarifa exacta ya no está disponible</li>
                                    </ol>
                                    <p class="mb-0"><i class="fas fa-sync-alt text-info me-2"></i> Siempre actualizamos los precios antes de redirigirte al sitio de reserva.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Favoritos Section -->
                <section id="favoritos" class="mb-5 pb-4">
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
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFav1">
                                    ¿Cómo funcionan las alertas de precio?
                                </button>
                            </h3>
                            <div id="collapseFav1" class="accordion-collapse collapse" data-bs-parent="#accordionFavoritos">
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
                                        <i class="fas fa-bell text-warning me-2"></i> Recibirás notificaciones por email y/o app (configurable).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Flight Tracker Section -->
                <section id="tracker" class="mb-5 pb-4">
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
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrack1">
                                    ¿Qué precisión tiene el Flight Tracker?
                                </button>
                            </h3>
                            <div id="collapseTrack1" class="accordion-collapse collapse" data-bs-parent="#accordionTracker">
                                <div class="accordion-body">
                                    <p>Nuestros datos tienen <strong>95-99% de precisión</strong> gracias a:</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded bg-light h-100">
                                                <h5 class="fw-bold"><i class="fas fa-satellite-dish text-primary me-2"></i> Fuentes</h5>
                                                <ul class="mb-0">
                                                    <li>Red global ADS-B</li>
                                                    <li>Datos MLAT</li>
                                                    <li>APIs de aerolíneas</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded bg-light h-100">
                                                <h5 class="fw-bold"><i class="fas fa-clock text-info me-2"></i> Actualización</h5>
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
                
                <!-- Contacto Final -->
                <div class="card border-0 shadow-sm mt-5 bg-gradient-primary text-white overflow-hidden">
                    <div class="card-body p-5 position-relative">
                        <div class="position-absolute top-0 end-0 opacity-20">
                            <i class="fas fa-question-circle fa-10x"></i>
                        </div>
                        <div class="position-relative">
                            <h2 class="fw-bold mb-3">¿Sigues con dudas?</h2>
                            <p class="lead mb-4">Nuestro equipo de expertos en vuelos está listo para ayudarte personalmente.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('help') }}" class="btn btn-light px-4">
                                    <i class="fas fa-envelope me-2"></i> Email
                                </a>
                                <a href="#" class="btn btn-outline-light px-4" data-bs-toggle="modal" data-bs-target="#chatModal">
                                    <i class="fas fa-comments me-2"></i> Chat 24/7
                                </a>
                                <a href="tel:+34900123456" class="btn btn-outline-light px-4">
                                    <i class="fas fa-phone-alt me-2"></i> +34 900 123 456
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
document.addEventListener('DOMContentLoaded', function() {
    // Filtrado de FAQs
    const faqSearch = document.getElementById('faqSearch');
    if (faqSearch) {
        faqSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    // Abre el acordeón si coincide
                    const collapse = item.querySelector('.accordion-collapse');
                    if (!collapse.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(collapse, { toggle: false });
                        bsCollapse.show();
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Scroll suave para anclas
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