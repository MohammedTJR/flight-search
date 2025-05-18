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
                    <button class="btn btn-primary btn-sm flex-grow-1" id="acceptCookies">
                        <i class="fas fa-check me-1"></i> Aceptar todas
                    </button>
                    <button class="btn btn-outline-light btn-sm flex-grow-1" id="rejectCookies">
                        <i class="fas fa-times me-1"></i> Rechazar
                    </button>
                    <button class="btn btn-link btn-sm text-white flex-grow-1" id="configCookies">
                        <i class="fas fa-cog me-1"></i> Configurar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Configuración (versión sin Bootstrap) -->
<div class="custom-modal" id="cookiesModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-primary text-white">
            <h5 class="custom-modal-title"><i class="fas fa-cookie-bite me-2"></i> Preferencias de Cookies</h5>
            <button type="button" class="custom-modal-close" id="closeCookiesModal">&times;</button>
        </div>
        <div class="custom-modal-body">
            <div class="mb-4">
                <p>Selecciona las cookies que deseas activar. Las cookies técnicas son necesarias para el
                    funcionamiento del sitio.</p>

                <div class="custom-form-check">
                    <input class="custom-form-check-input" type="checkbox" id="essentialCookies" checked disabled>
                    <label class="custom-form-check-label" for="essentialCookies">Cookies Esenciales</label>
                    <p class="small text-muted mt-1 mb-0">Necesarias para el funcionamiento básico del sitio. No se
                        pueden desactivar.</p>
                </div>

                <div class="custom-form-check">
                    <input class="custom-form-check-input" type="checkbox" id="analyticsCookies">
                    <label class="custom-form-check-label" for="analyticsCookies">Cookies de Análisis</label>
                    <p class="small text-muted mt-1 mb-0">Nos permiten medir el tráfico y analizar tu comportamiento
                        para mejorar el servicio.</p>
                </div>

                <div class="custom-form-check">
                    <input class="custom-form-check-input" type="checkbox" id="marketingCookies">
                    <label class="custom-form-check-label" for="marketingCookies">Cookies de Marketing</label>
                    <p class="small text-muted mt-1 mb-0">Para mostrarte publicidad personalizada basada en tus
                        intereses.</p>
                </div>
            </div>

            <div class="custom-alert">
                <i class="fas fa-info-circle me-2"></i> Puedes cambiar estas preferencias en cualquier momento desde
                el enlace en el pie de página.
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-outline-secondary" id="cancelCookiesModal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="saveCookiePreferences">Guardar preferencias</button>
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
            saveCookiePreferences({
                essential: true,
                analytics: true,
                marketing: true
            });
        });

        // Manejar rechazar
        document.getElementById('rejectCookies')?.addEventListener('click', function () {
            saveCookiePreferences({
                essential: true,
                analytics: false,
                marketing: false
            });
        });

        // Abrir modal de configuración
        document.getElementById('configCookies')?.addEventListener('click', function (e) {
            e.preventDefault();
            showCookiesModal();
        });

        // Cerrar modal al hacer clic en la X
        document.getElementById('closeCookiesModal')?.addEventListener('click', function () {
            hideCookiesModal();
        });

        // Cerrar modal al hacer clic en Cancelar
        document.getElementById('cancelCookiesModal')?.addEventListener('click', function () {
            hideCookiesModal();
        });

        // Guardar preferencias personalizadas
        document.getElementById('saveCookiePreferences')?.addEventListener('click', function () {
            const preferences = {
                essential: true,
                analytics: document.getElementById('analyticsCookies').checked,
                marketing: document.getElementById('marketingCookies').checked
            };
            saveCookiePreferences(preferences);
            hideCookiesModal();
        });

        // Cerrar modal al hacer clic fuera del contenido
        document.getElementById('cookiesModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                hideCookiesModal();
            }
        });

        // Función para mostrar el modal
        function showCookiesModal() {
            const modal = document.getElementById('cookiesModal');
            modal.style.display = 'block';

            // Cargar preferencias existentes
            const preferences = JSON.parse(localStorage.getItem('cookiePreferences')) || {
                essential: true,
                analytics: false,
                marketing: false
            };

            document.getElementById('analyticsCookies').checked = preferences.analytics;
            document.getElementById('marketingCookies').checked = preferences.marketing;
        }

        // Función para ocultar el modal
        function hideCookiesModal() {
            document.getElementById('cookiesModal').style.display = 'none';
        }

        // Función para guardar preferencias
        function saveCookiePreferences(preferences) {
            preferences.timestamp = new Date().getTime();
            localStorage.setItem('cookiePreferences', JSON.stringify(preferences));
            document.getElementById('cookiesBanner').classList.add('d-none');
            loadCookies();
        }

        // Cargar cookies según preferencias
        function loadCookies() {
            const preferences = JSON.parse(localStorage.getItem('cookiePreferences')) || {
                essential: true,
                analytics: false,
                marketing: false
            };

            // Cargar Google Analytics solo si está permitido
            if (preferences.analytics) {
                console.log('Cargando cookies de análisis...');
                // Aquí iría tu código de Google Analytics
            }

            // Cargar cookies de marketing solo si está permitido
            if (preferences.marketing) {
                console.log('Cargando cookies de marketing...');
                // Aquí iría tu código de Facebook Pixel, Google Ads, etc.
            }
        }

        // Cargar cookies al iniciar (si hay preferencias)
        if (localStorage.getItem('cookiePreferences')) {
            loadCookies();
        }
    });
</script>