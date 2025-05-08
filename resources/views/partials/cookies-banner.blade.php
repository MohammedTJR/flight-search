<div class="cookies-banner fixed-bottom bg-dark text-white p-4 d-none" id="cookiesBanner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h5 class="fw-bold mb-2"><i class="fas fa-cookie-bite me-2"></i> Política de Cookies</h5>
                <p class="small mb-0">Utilizamos cookies propias y de terceros para mejorar nuestros servicios, analizar
                    tus hábitos de navegación y mostrarte publicidad relacionada con tus preferencias. Puedes gestionar
                    o rechazar su uso haciendo clic en "Configurar".</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <button class="btn btn-outline-light btn-sm flex-grow-1" id="rejectCookies">
                        <i class="fas fa-times me-1"></i> Rechazar
                    </button>
                    <button class="btn btn-primary btn-sm flex-grow-1" id="acceptCookies">
                        <i class="fas fa-check me-1"></i> Aceptar todas
                    </button>
                    <a href="{{ route('cookies.policy') }}" class="btn btn-link btn-sm text-white flex-grow-1">
                        <i class="fas fa-cog me-1"></i> Configurar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Configuración -->
<div class="modal fade" id="cookiesModal" tabindex="-1" aria-labelledby="cookiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="cookiesModalLabel"><i class="fas fa-cookie-bite me-2"></i> Preferencias de
                    Cookies</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <p>Selecciona las cookies que deseas activar. Las cookies técnicas son necesarias para el
                        funcionamiento del sitio.</p>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="essentialCookies" checked disabled>
                        <label class="form-check-label fw-bold" for="essentialCookies">Cookies Esenciales</label>
                        <p class="small text-muted mt-1 mb-0">Necesarias para el funcionamiento básico del sitio. No se
                            pueden desactivar.</p>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="analyticsCookies">
                        <label class="form-check-label fw-bold" for="analyticsCookies">Cookies de Análisis</label>
                        <p class="small text-muted mt-1 mb-0">Nos permiten medir el tráfico y analizar tu comportamiento
                            para mejorar el servicio.</p>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="marketingCookies">
                        <label class="form-check-label fw-bold" for="marketingCookies">Cookies de Marketing</label>
                        <p class="small text-muted mt-1 mb-0">Para mostrarte publicidad personalizada basada en tus
                            intereses.</p>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Puedes cambiar estas preferencias en cualquier momento desde
                    el enlace en el pie de página.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveCookiePreferences">Guardar preferencias</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mostrar banner si no hay preferencias guardadas
        if (!localStorage.getItem('cookiePreferences')) {
        setTimeout(() => {
            document.getElementById('cookiesBanner').classList.remove('d-none');
        }, 1000);
        }

        // Manejar aceptar todas
        document.getElementById('acceptCookies')?.addEventListener('click', function () {
            localStorage.setItem('cookiePreferences', JSON.stringify({
                essential: true,
                analytics: true,
                marketing: true,
                timestamp: new Date().getTime()
            }));
            document.getElementById('cookiesBanner').classList.add('d-none');
            loadCookies();
        });

        // Manejar rechazar
        document.getElementById('rejectCookies')?.addEventListener('click', function () {
            localStorage.setItem('cookiePreferences', JSON.stringify({
                essential: true,
                analytics: false,
                marketing: false,
                timestamp: new Date().getTime()
            }));
            document.getElementById('cookiesBanner').classList.add('d-none');
            loadCookies();
        });

        // Abrir modal de configuración
        document.querySelector('a[href="{{ route('cookies.policy') }}"]')?.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('cookiesModal'));
            modal.show();

            // Cargar preferencias existentes
            const preferences = JSON.parse(localStorage.getItem('cookiePreferences')) || {
                essential: true,
                analytics: false,
                marketing: false
            };

            document.getElementById('analyticsCookies').checked = preferences.analytics;
            document.getElementById('marketingCookies').checked = preferences.marketing;
        });

        // Guardar preferencias personalizadas
        document.getElementById('saveCookiePreferences')?.addEventListener('click', function () {
            const preferences = {
                essential: true,
                analytics: document.getElementById('analyticsCookies').checked,
                marketing: document.getElementById('marketingCookies').checked,
                timestamp: new Date().getTime()
            };

            localStorage.setItem('cookiePreferences', JSON.stringify(preferences));
            bootstrap.Modal.getInstance(document.getElementById('cookiesModal')).hide();
            document.getElementById('cookiesBanner').classList.add('d-none');
            loadCookies();
        });

        // Cargar cookies según preferencias
        function loadCookies() {
            const preferences = JSON.parse(localStorage.getItem('cookiePreferences')) || {
                essential: true,
                analytics: false,
                marketing: false
            };

            // Cargar Google Analytics solo si está permitido
            if (preferences.analytics) {
                // Aquí iría tu código de Google Analytics
                console.log('Cargando cookies de análisis...');
            }

            // Cargar cookies de marketing solo si está permitido
            if (preferences.marketing) {
                // Aquí iría tu código de Facebook Pixel, Google Ads, etc.
                console.log('Cargando cookies de marketing...');
            }
        }

        // Cargar cookies al iniciar (si hay preferencias)
        if (localStorage.getItem('cookiePreferences')) {
            loadCookies();
        }
    });
</script>