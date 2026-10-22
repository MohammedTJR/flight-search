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
<div class="custom-modal" id="cookiesModal" role="dialog" aria-modal="true" aria-labelledby="cookiesModalTitle">
    <div class="ck-modal">
        <div class="ck-bar"></div>

        <div class="ck-head">
            <div class="ck-head__title">
                <span class="ck-head__icon"><i class="fas fa-cookie-bite"></i></span>
                <div>
                    <small>Privacidad · FlyLow</small>
                    <h2 id="cookiesModalTitle">Preferencias de Cookies</h2>
                </div>
            </div>
            <button type="button" class="ck-close" id="closeCookiesModal" aria-label="Cerrar">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="ck-body">
            <p class="ck-intro">Selecciona las cookies que deseas activar. Las cookies técnicas son necesarias para el
                funcionamiento del sitio.</p>

            <div class="ck-card ck-card--fixed">
                <span class="ck-box is-on"><i class="fas fa-check"></i></span>
                <div class="ck-card__text">
                    <div class="ck-card__title">
                        <strong>Cookies Esenciales</strong>
                        <span class="ck-tag ck-tag--on"><i></i>Siempre activas</span>
                    </div>
                    <p>Necesarias para el funcionamiento básico del sitio, la navegación segura y el mantenimiento de
                        tu sesión. No se pueden desactivar.</p>
                </div>
                <i class="fas fa-lock ck-lock" title="Necesarias por seguridad"></i>
            </div>

            <label class="ck-card ck-card--option" for="analyticsCookies">
                <input class="ck-check" type="checkbox" id="analyticsCookies">
                <span class="ck-box"><i class="fas fa-check"></i></span>
                <div class="ck-card__text">
                    <div class="ck-card__title">
                        <strong>Cookies de Análisis</strong>
                        <span class="ck-tag">Estadísticas</span>
                    </div>
                    <p>Nos permiten medir el tráfico y analizar el uso del sitio para mejorar el servicio.</p>
                </div>
            </label>

            <label class="ck-card ck-card--option" for="marketingCookies">
                <input class="ck-check" type="checkbox" id="marketingCookies">
                <span class="ck-box"><i class="fas fa-check"></i></span>
                <div class="ck-card__text">
                    <div class="ck-card__title">
                        <strong>Cookies de Marketing</strong>
                        <span class="ck-tag">Opcional</span>
                    </div>
                    <p>Para mostrarte publicidad personalizada basada en tus intereses.</p>
                </div>
            </label>

            <div class="ck-info">
                <i class="fas fa-circle-info"></i>
                <p>Puedes cambiar estas preferencias en cualquier momento desde el enlace
                    <strong>«Configuración de cookies»</strong> del pie de página.</p>
            </div>
        </div>

        <div class="ck-foot">
            <button type="button" class="ck-btn ck-btn--ghost" id="essentialOnlyCookies">Aceptar solo esenciales</button>
            <div class="ck-foot__main">
                <button type="button" class="ck-btn ck-btn--ghost" id="cancelCookiesModal">Cancelar</button>
                <button type="button" class="ck-btn ck-btn--primary" id="saveCookiePreferences">
                    <i class="fas fa-shield-halved"></i>Guardar preferencias
                </button>
            </div>
        </div>

        <div class="ck-toast" id="cookiesToast" role="status">
            <i class="fas fa-circle-check"></i><span>Preferencias guardadas</span>
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
            confirmAndClose('Preferencias guardadas');
        });

        // Aceptar solo las esenciales desde el modal
        document.getElementById('essentialOnlyCookies')?.addEventListener('click', function () {
            document.getElementById('analyticsCookies').checked = false;
            document.getElementById('marketingCookies').checked = false;
            saveCookiePreferences({
                essential: true,
                analytics: false,
                marketing: false
            });
            confirmAndClose('Solo cookies esenciales');
        });

        // Cerrar modal al hacer clic fuera del contenido
        document.getElementById('cookiesModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                hideCookiesModal();
            }
        });

        // Cerrar con Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                hideCookiesModal();
            }
        });

        // Mensaje de confirmación y cierre
        function confirmAndClose(message) {
            const toast = document.getElementById('cookiesToast');
            toast.querySelector('span').textContent = message;
            toast.classList.add('is-visible');
            setTimeout(() => {
                toast.classList.remove('is-visible');
                hideCookiesModal();
            }, 900);
        }

        // Función para mostrar el modal
        function showCookiesModal() {
            const modal = document.getElementById('cookiesModal');
            modal.style.display = 'flex';

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

        // Lo usa el enlace «Configuración de cookies» del pie de página
        window.openCookiePreferences = showCookiesModal;

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
        }

        // Cargar cookies al iniciar (si hay preferencias)
        if (localStorage.getItem('cookiePreferences')) {
            loadCookies();
        }
    });
</script>