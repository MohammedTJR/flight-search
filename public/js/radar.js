// Variables globales
let map;
let baseLayers = {};
let markers = {};
let flightPaths = {}; // Para almacenar trayectorias
let selectedFlight = null;
let followFlight = null; // ICAO24 del avión que se está siguiendo
let routeFlown = null;     // Tramo recorrido (origen -> avión)
let routeRemain = null;    // Tramo restante (avión -> destino)
let routeMarkers = [];     // Marcadores de los aeropuertos de la ruta
let currentRoute = null;   // Datos de la ruta actual {origin, destination}
let flightTrail = [];      // Posiciones reales observadas del avión (giros reales)
let trailIcao = null;      // ICAO24 al que pertenece flightTrail
const routeCache = {};     // Caché de rutas por callsign (evita repetir peticiones)
let currentRouteCallsign = null; // Callsign cuya ruta se está mostrando
let routeDrawnFor = null;  // Callsign cuya ruta ya está dibujada (evita redibujar)
let updateTimer;
let countdown = 60;
let animationFrame; // Para manejar la animación
let lastUpdateTime = 0;

// --- Radar mundial: carga por zona visible ---
const MIN_ZOOM_FOR_FLIGHTS = 6; // Por debajo de este zoom no se dibujan aviones
const MAX_FLIGHTS = 1200;        // Tope de seguridad de marcadores por carga
let moveDebounce = null;

// Elimina todos los marcadores del mapa
function clearAllMarkers() {
    Object.keys(markers).forEach(icao24 => {
        map.removeLayer(markers[icao24]);
        delete markers[icao24];
    });
    if (selectedFlight) {
        closeFlightDetails();
        selectedFlight = null;
    }
}

// Muestra/oculta el aviso de "acércate para ver vuelos"
function toggleZoomHint(show) {
    let hint = document.getElementById('zoom-hint');
    if (show) {
        if (!hint) {
            hint = document.createElement('div');
            hint.id = 'zoom-hint';
            hint.innerHTML = '<i class="fas fa-magnifying-glass-plus me-2"></i>Acércate para ver los vuelos de la zona';
            hint.style.cssText = 'position:absolute;top:12px;left:50%;transform:translateX(-50%);z-index:1000;' +
                'background:rgba(10,31,56,0.85);color:#fff;padding:0.55rem 1rem;border-radius:2rem;' +
                'font-size:0.9rem;display:flex;align-items:center;box-shadow:0 4px 14px rgba(0,0,0,0.25);' +
                'pointer-events:none;white-space:nowrap;';
            document.getElementById('map').appendChild(hint);
        }
        hint.style.display = 'flex';
    } else if (hint) {
        hint.style.display = 'none';
    }
}

function isMobileDevice() {
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
}

function shouldShowMobileWarning() {
    return !localStorage.getItem('mobileWarningShown');
}

function showMobileWarningIfNeeded() {
    if (isMobileDevice() && shouldShowMobileWarning()) {
        const overlay = document.getElementById('mobile-warning-overlay');
        overlay.style.display = 'flex';

        document.getElementById('mobile-warning-close').addEventListener('click', function () {
            overlay.style.display = 'none';
            localStorage.setItem('mobileWarningShown', 'true');
        });
    }
}

const mapLayers = {
    osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }),

    esri: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles © Esri',
        maxZoom: 18
    }),

    opentopomap: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenTopoMap',
        maxZoom: 17
    }),

    dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© CartoDB'
    })
};

// Función para inicializar el mapa
function initializeMap() {

    map = L.map('map').setView([40.416775, -3.703790], 6);

    // Capa por defecto
    mapLayers.osm.addTo(map);

    // Al mover o hacer zoom, recargar los aviones del nuevo área visible (con debounce)
    map.on('moveend', function () {
        clearTimeout(moveDebounce);
        moveDebounce = setTimeout(function () {
            countdown = 60;
            fetchFlights();
        }, 1000);
    });

    // Controlador del botón - versión más robusta
    const toggleBtn = document.getElementById('toggle-map-style');
    const optionsPanel = document.getElementById('map-style-options');

    if (!toggleBtn || !optionsPanel) {
        console.error('Elementos del control de mapa no encontrados!');
        return;
    }

    toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        optionsPanel.classList.toggle('show');
    });

    // Cierra al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!optionsPanel.contains(e.target) && e.target !== toggleBtn) {
            optionsPanel.classList.remove('show');
        }
    });

    // Selección de capa
    document.querySelectorAll('.map-option').forEach(option => {
        option.addEventListener('click', function (e) {
            e.stopPropagation();
            const selectedLayer = this.dataset.layer;

            Object.values(mapLayers).forEach(layer => map.removeLayer(layer));
            mapLayers[selectedLayer].addTo(map);

            optionsPanel.classList.remove('show');

            // Actualiza el ícono del botón
            const iconClass = {
                'osm': 'fa-map',
                'esri': 'fa-satellite',
                'opentopomap': 'fa-mountain',
                'dark': 'fa-moon'
            }[selectedLayer];

            toggleBtn.innerHTML = `<i class="fas ${iconClass}"></i>`;
        });
    });

}

// Inicializar el mapa
document.addEventListener('DOMContentLoaded', function () {
    showMobileWarningIfNeeded();
    initializeMap();
    fetchFlights();

    // Iniciar el bucle de animación
    lastUpdateTime = performance.now();
    animateFlights();

    // Actualizar cada 60 segundos
    updateTimer = setInterval(function () {
        countdown--;
        document.getElementById('next-update').textContent = `Próxima actualización en ${countdown} segundos`;

        if (countdown <= 0) {
            countdown = 60;
            fetchFlights();
        }
    }, 1000);
});

// Función para calcular la nueva posición geográfica
function calculateNewPosition(lat, lng, velocity, heading, deltaTime) {
    const R = 6371000; // Radio de la Tierra en metros
    const distance = velocity * deltaTime; // Distancia recorrida en metros
    const headingRad = heading * (Math.PI / 180); // Convertir a radianes

    const latRad = lat * (Math.PI / 180);
    const lngRad = lng * (Math.PI / 180);

    const newLatRad = Math.asin(
        Math.sin(latRad) * Math.cos(distance / R) +
        Math.cos(latRad) * Math.sin(distance / R) * Math.cos(headingRad)
    );

    const newLngRad = lngRad + Math.atan2(
        Math.sin(headingRad) * Math.sin(distance / R) * Math.cos(latRad),
        Math.cos(distance / R) - Math.sin(latRad) * Math.sin(newLatRad)
    );

    const newLat = newLatRad * (180 / Math.PI);
    const newLng = newLngRad * (180 / Math.PI);

    return [newLat, newLng];
}

// Función de animación
function animateFlights() {
    const now = performance.now();
    const deltaTime = Math.min((now - lastUpdateTime) / 1000, 0.1); // Limitar a 100ms máximo
    lastUpdateTime = now;

    const markersToUpdate = Object.keys(markers);
    const markersCount = markersToUpdate.length;

    for (let i = 0; i < markersCount; i++) {
        const icao24 = markersToUpdate[i];
        const marker = markers[icao24];
        const flight = marker.flightData;

        if (!flight || flight[8]) continue; // Saltar si no hay datos o está en tierra

        const velocity = flight[9];
        const heading = flight[10];

        if (velocity && heading !== null) {
            const [newLat, newLng] = calculateNewPosition(
                marker.getLatLng().lat,
                marker.getLatLng().lng,
                velocity,
                heading,
                deltaTime
            );

            marker.setLatLng([newLat, newLng]);

            // Actualizar datos de posición
            flight[5] = newLng;
            flight[6] = newLat;

            // Si el avión está siendo seguido, mover el mapa
            if (followFlight === icao24) {
                map.setView([newLat, newLng]);
            }
        }
    }

    // Recolocar la ruta para que pase por la posición actual del avión
    if (currentRoute && selectedFlight) {
        updateRouteGeometry();
    }

    animationFrame = requestAnimationFrame(animateFlights);
}

// Carga los vuelos del área visible (o avisa si el zoom es demasiado bajo)
function fetchFlights() {
    // Cancelar animación previa para evitar acumulación
    if (animationFrame) {
        cancelAnimationFrame(animationFrame);
    }

    // Zoom mínimo: con el mundo entero habría demasiados aviones, así que
    // por debajo del umbral no se cargan y se muestra un aviso.
    if (map.getZoom() < MIN_ZOOM_FOR_FLIGHTS) {
        // Quitar todos los aviones salvo el seleccionado/seguido, para no perderlo
        // ni su ruta al alejar el zoom (p. ej. para ver el trayecto completo).
        Object.keys(markers).forEach(icao24 => {
            if (icao24 !== selectedFlight && icao24 !== followFlight) {
                map.removeLayer(markers[icao24]);
                delete markers[icao24];
            }
        });
        updateStatistics(Object.keys(markers).map(k => markers[k].flightData));
        document.getElementById('loading-overlay').style.display = 'none';
        toggleZoomHint(true);
        // Mantener la animación (y la ruta) si queda algún avión
        lastUpdateTime = performance.now();
        animateFlights();
        return;
    }
    toggleZoomHint(false);

    if (Object.keys(markers).length === 0) {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    // Bounding box del área visible, recortado a rangos válidos del mundo
    const b = map.getBounds();
    const lamin = Math.max(-90, b.getSouth());
    const lamax = Math.min(90, b.getNorth());
    const lomin = Math.max(-180, b.getWest());
    const lomax = Math.min(180, b.getEast());
    const url = `/api/radar?lamin=${lamin.toFixed(4)}&lamax=${lamax.toFixed(4)}` +
        `&lomin=${lomin.toFixed(4)}&lomax=${lomax.toFixed(4)}`;

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.error) throw new Error(data.error);
            // Tope de seguridad por si una zona muy densa devuelve demasiados
            let states = data.states || [];
            if (states.length > MAX_FLIGHTS) {
                states = states.slice(0, MAX_FLIGHTS);
            }
            updateFlights(states);
            updateStatistics(states);
            document.getElementById('loading-overlay').style.display = 'none';

            const now = new Date();
            document.getElementById('last-update').textContent =
                `Última actualización: ${now.toLocaleTimeString()}`;

            // Reiniciar animación con los nuevos datos
            lastUpdateTime = performance.now();
            animateFlights();
        })
        .catch(error => {
            console.error('Error al obtener los datos de vuelos:', error);
            document.getElementById('loading-overlay').style.display = 'none';

            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = 'Error al cargar datos: ' + error.message;

            const header = document.querySelector('.radar-header');
            header.appendChild(errorDiv);

            setTimeout(() => errorDiv.remove(), 5000);

            // Reiniciar animación incluso con error para mantener los aviones existentes en movimiento
            lastUpdateTime = performance.now();
            animateFlights();
        });
}

function updateStatistics(flightData) {
    // Total de vuelos
    document.getElementById('total-flights').textContent = flightData.length;

    // Total de países únicos
    const countries = new Set();
    let totalAltitude = 0;
    let validAltitudes = 0;
    let totalSpeed = 0;
    let validSpeeds = 0;

    flightData.forEach(flight => {
        // Países
        if (flight[2]) {
            countries.add(flight[2]);
        }

        // Altitud
        if (flight[7] !== null) {
            totalAltitude += flight[7];
            validAltitudes++;
        }

        // Velocidad
        if (flight[9] !== null) {
            totalSpeed += flight[9];
            validSpeeds++;
        }
    });

    document.getElementById('total-countries').textContent = countries.size;

    // Altitud y velocidad media
    const avgAltitude = validAltitudes > 0 ? Math.round(totalAltitude / validAltitudes) : 0;
    const avgSpeed = validSpeeds > 0 ? Math.round((totalSpeed / validSpeeds) * 3.6) : 0; // m/s a km/h

    document.getElementById('avg-altitude').textContent = avgAltitude;
    document.getElementById('avg-speed').textContent = avgSpeed;
}

function updateFlights(flightData) {
    const activeFlights = new Set();

    flightData.forEach(flight => {
        const icao24 = flight[0];
        const latitude = flight[6];
        const longitude = flight[5];
        const trueTrack = flight[10];
        const velocity = flight[9];

        if (latitude && longitude) {
            activeFlights.add(icao24);

            if (markers[icao24]) {
                // Actualizar posición del marcador
                markers[icao24].setLatLng([latitude, longitude]);

                // Actualizar datos de vuelo
                markers[icao24].flightData = flight;

                // Registrar la posición real del avión seleccionado (giros reales)
                if (icao24 === selectedFlight) {
                    if (trailIcao !== selectedFlight) {
                        flightTrail = [];
                        trailIcao = selectedFlight;
                    }
                    flightTrail.push([latitude, longitude]);
                    if (flightTrail.length > 300) flightTrail.shift();
                }

                // Actualizar rotación del marcador
                if (trueTrack !== null) {
                    const iconElement = markers[icao24].getElement();
                    if (iconElement) {
                        const planeIcon = iconElement.querySelector('.aircraft-icon');
                        if (planeIcon) {
                            const adjustedRotation = trueTrack - 90; // Compensar si el ícono apunta hacia la derecha
                            planeIcon.style.transform = `rotate(${adjustedRotation}deg)`;
                            planeIcon.style.transformOrigin = 'center';
                        }
                    }
                }

                // Si el avión está seleccionado, actualizar el panel de detalles
                if (selectedFlight === icao24) {
                    showFlightDetails(flight); // Actualizar el contenido del panel
                }
            } else {
                // Crear nuevo marcador si no existe
                const icon = L.divIcon({
                    html: '<i class="fas fa-plane aircraft-icon"></i>',
                    className: 'aircraft-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                const marker = L.marker([latitude, longitude], { icon: icon }).addTo(map);

                // Rotación inicial
                if (trueTrack !== null) {
                    const iconElement = marker.getElement();
                    if (iconElement) {
                        const planeIcon = iconElement.querySelector('.aircraft-icon');
                        if (planeIcon) {
                            const adjustedRotation = trueTrack - 90;
                            planeIcon.style.transform = `rotate(${adjustedRotation}deg)`;
                            planeIcon.style.transformOrigin = 'center';
                        }
                    }
                }

                marker.flightData = flight;
                marker.on('click', function () {
                    showFlightDetails(flight);
                    if (selectedFlight && markers[selectedFlight]) {
                        const prevIcon = markers[selectedFlight].getElement().querySelector('.aircraft-icon');
                        if (prevIcon) prevIcon.style.color = '#0d6efd';
                    }
                    const currentIcon = marker.getElement().querySelector('.aircraft-icon');
                    if (currentIcon) currentIcon.style.color = '#ff4500';
                    selectedFlight = icao24;
                });

                markers[icao24] = marker;
            }
        }
    });

    // Eliminar marcadores inactivos
    Object.keys(markers).forEach(icao24 => {
        if (!activeFlights.has(icao24)) {
            // No eliminar el avión seleccionado o seguido aunque no venga en los
            // datos del área visible: así no se pierde al hacer zoom o refrescar.
            if (icao24 === selectedFlight || icao24 === followFlight) return;
            map.removeLayer(markers[icao24]);
            delete markers[icao24];
        }
    });
}

function togglePanel() {
    const panel = document.getElementById('flight-details-panel');
    if (panel.classList.contains('show')) {
        closeFlightDetails();
    } else {
        panel.classList.add('show');
    }
}

// Quita la ruta (tramos y marcadores de aeropuertos) del mapa
function clearRoute() {
    if (routeFlown) { map.removeLayer(routeFlown); routeFlown = null; }
    if (routeRemain) { map.removeLayer(routeRemain); routeRemain = null; }
    routeMarkers.forEach(m => map.removeLayer(m));
    routeMarkers = [];
    currentRoute = null;
    flightTrail = [];
    trailIcao = null;
    routeDrawnFor = null;
}

// Dibuja la ruta en dos tramos (origen -> avión -> destino) con los aeropuertos
function drawRoute(route) {
    clearRoute();
    if (!route || !route.origin || !route.destination) return;

    const o = route.origin, d = route.destination;
    if (o.lat == null || o.lng == null || d.lat == null || d.lng == null) return;

    currentRoute = route;

    // Tramo recorrido (origen -> giros reales -> avión): línea sólida
    routeFlown = L.polyline([], { color: '#0b7a9e', weight: 3, opacity: 0.9 }).addTo(map);
    // Tramo restante (avión -> destino): discontinua y más tenue
    routeRemain = L.polyline([], {
        color: '#0b7a9e', weight: 2.5, opacity: 0.55, dashArray: '8, 8'
    }).addTo(map);

    [[o, 'Origen'], [d, 'Destino']].forEach(([ap, label]) => {
        const m = L.circleMarker([ap.lat, ap.lng], {
            radius: 6, color: '#0b7a9e', fillColor: '#fff', fillOpacity: 1, weight: 2
        }).addTo(map);
        m.bindTooltip(`${label}: ${ap.name || ap.iata || ''}`, { direction: 'top' });
        routeMarkers.push(m);
    });

    updateRouteGeometry();
}

// Recoloca los tramos para que la ruta pase por la posición actual del avión
function updateRouteGeometry() {
    if (!currentRoute || !routeFlown || !routeRemain) return;

    const o = currentRoute.origin, d = currentRoute.destination;
    const m = selectedFlight && markers[selectedFlight];

    if (m) {
        const p = m.getLatLng();
        // Recorrido: origen -> giros reales observados -> avión
        routeFlown.setLatLngs([[o.lat, o.lng], ...flightTrail, [p.lat, p.lng]]);
        // Restante: avión -> destino
        routeRemain.setLatLngs([[p.lat, p.lng], [d.lat, d.lng]]);
    } else {
        // Sin avión localizado: línea directa origen -> destino
        routeFlown.setLatLngs([[o.lat, o.lng], [d.lat, d.lng]]);
        routeRemain.setLatLngs([]);
    }
}

// HTML de la sección de ruta para el panel de detalles
function renderRouteSection(route) {
    if (route === 'loading') {
        return '<div class="text-muted small"><i class="fas fa-spinner fa-spin me-2"></i>Buscando ruta...</div>';
    }
    if (!route) {
        return '<div class="text-muted small"><i class="fas fa-circle-info me-2"></i>Ruta no disponible para este vuelo.</div>';
    }
    const ap = (a) => `${a.name || 'Aeropuerto'}${a.iata ? ' (' + a.iata + ')' : ''}` +
        (a.municipality ? `<div class="text-muted small">${a.municipality}${a.country ? ', ' + a.country : ''}</div>` : '');
    return `
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-plane-departure me-2"></i>Origen:</div>
            <div class="detail-value">${ap(route.origin)}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-plane-arrival me-2"></i>Destino:</div>
            <div class="detail-value">${ap(route.destination)}</div>
        </div>`;
}

// Carga (y cachea) la ruta de un callsign, actualiza el panel y dibuja la línea
function loadRoute(callsign) {
    callsign = (callsign || '').trim();
    currentRouteCallsign = callsign;

    const apply = (route) => {
        // Si el usuario ya cambió de avión, no pisar el panel
        if (currentRouteCallsign !== callsign) return;
        const section = document.getElementById('route-section');
        if (section) section.innerHTML = renderRouteSection(route);
        // Dibujar la ruta solo en una selección nueva, no en cada refresco del
        // panel (así no se borra el rastro de giros reales acumulado).
        if (routeDrawnFor !== callsign) {
            drawRoute(route);
            routeDrawnFor = route ? callsign : null;
        }
    };

    if (!callsign || callsign === 'N/A') {
        apply(null);
        return;
    }

    if (routeCache[callsign] !== undefined) {
        apply(routeCache[callsign]);
        return;
    }

    fetch(`/api/radar/route/${encodeURIComponent(callsign)}`)
        .then(r => r.ok ? r.json() : { route: null })
        .then(data => {
            routeCache[callsign] = data.route || null;
            apply(routeCache[callsign]);
        })
        .catch(() => {
            routeCache[callsign] = null;
            apply(null);
        });
}

function showFlightDetails(flight) {
    const icao24 = flight[0];
    const callsign = flight[1] ? flight[1].trim() : 'N/A';
    const originCountry = flight[2] || 'Desconocido';
    const lastUpdate = new Date(flight[4] * 1000).toLocaleString();
    const altitude = flight[7] ? `${Math.round(flight[7])} metros` : 'N/A';
    const velocity = flight[9] ? `${Math.round(flight[9] * 3.6)} km/h` : 'N/A';
    const heading = flight[10] ? `${Math.round(flight[10])}°` : 'N/A';
    const verticalRate = flight[11] !== null ? `${flight[11].toFixed(1)} m/s` : 'N/A';
    const onGround = flight[8] ? 'Sí' : 'No';
    const squawk = flight[14] || 'N/A';

    // Interpretar fuente de posición
    let positionSource = 'Desconocida';
    if (flight[16] === 0) positionSource = 'ADS-B';
    else if (flight[16] === 1) positionSource = 'ASTERIX';
    else if (flight[16] === 2) positionSource = 'MLAT';
    else if (flight[16] === 3) positionSource = 'FLARM';

    // Crear contenido HTML para el panel
    const detailsHTML = `
        <div class="flight-title">
            <h4><i class="fas fa-plane me-2"></i>${callsign}</h4>
            <p class="mb-0 text-muted">${icao24.toUpperCase()} - ${originCountry}</p>
        </div>

        <div class="info-section">
            <h5><i class="fas fa-route me-2"></i>Ruta</h5>
            <div id="route-section">${renderRouteSection('loading')}</div>
        </div>

        <div class="info-section">
            <h5><i class="fas fa-info-circle me-2"></i>Información General</h5>
            <div class="detail-row">
                <div class="detail-label">ICAO24:</div>
                <div class="detail-value">${icao24.toUpperCase()}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Callsign:</div>
                <div class="detail-value">${callsign}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">País:</div>
                <div class="detail-value">${originCountry}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Squawk:</div>
                <div class="detail-value">${squawk}</div>
            </div>
        </div>

        <div class="info-section">
            <h5><i class="fas fa-chart-line me-2"></i>Datos de Vuelo</h5>
            <div class="detail-row">
                <div class="detail-label">Altitud:</div>
                <div class="detail-value">${altitude}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Velocidad:</div>
                <div class="detail-value">${velocity}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Dirección:</div>
                <div class="detail-value">${heading}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tasa Vertical:</div>
                <div class="detail-value">${verticalRate}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">En tierra:</div>
                <div class="detail-value">${onGround}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Fuente de datos:</div>
                <div class="detail-value">${positionSource}</div>
            </div>
        </div>

        <div class="info-section">
            <h5><i class="fas fa-clock me-2"></i>Tiempo</h5>
            <div class="detail-row">
                <div class="detail-label">Última actualización:</div>
                <div class="detail-value">${lastUpdate}</div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-outline-primary w-100" onclick="centerOnFlight('${icao24}')">
                <i class="fas fa-search-location me-2"></i>Centrar en el mapa
            </button>
        </div>
    `;

    // Actualizar contenido y mostrar panel
    document.getElementById('flight-details-content').innerHTML = detailsHTML;
    document.getElementById('flight-details-panel').classList.add('show');

    // Cargar la ruta origen -> destino (por callsign) y dibujarla en el mapa
    loadRoute(callsign);
}

function closeFlightDetails() {
    document.getElementById('flight-details-panel').classList.remove('show');

    // Restaurar color del marcador seleccionado
    if (selectedFlight && markers[selectedFlight]) {
        markers[selectedFlight].getElement().querySelector('i').style.color = '#0d6efd';
        selectedFlight = null;
    }

    followFlight = null; // Desactivar el seguimiento
    currentRouteCallsign = null;
    clearRoute(); // Quitar la ruta del mapa
}

function centerOnFlight(icao24) {
    if (markers[icao24]) {
        const position = markers[icao24].getLatLng();
        map.panTo(position); // Centrar el mapa en el avión sin cambiar el zoom
        followFlight = icao24; // Activar el seguimiento del avión
    }
}

function searchFlight() {
    const searchValue = document.getElementById('flight-search').value.trim().toLowerCase();

    if (!searchValue) {
        alert('Por favor, introduce un ICAO24 o Callsign para buscar.');
        return;
    }

    // Buscar el avión en los marcadores
    let found = false;
    Object.keys(markers).forEach(icao24 => {
        const flight = markers[icao24].flightData;
        const callsign = flight[1] ? flight[1].trim().toLowerCase() : '';

        if (icao24.toLowerCase() === searchValue || callsign === searchValue) {
            // Centrar el mapa en el avión encontrado
            const position = markers[icao24].getLatLng();
            map.panTo(position);

            // Mostrar detalles del vuelo
            showFlightDetails(flight);

            // Resaltar el marcador seleccionado
            if (selectedFlight && markers[selectedFlight]) {
                const prevIcon = markers[selectedFlight].getElement().querySelector('.aircraft-icon');
                if (prevIcon) prevIcon.style.color = '#0d6efd'; // Restaurar color del marcador anterior
            }

            const currentIcon = markers[icao24].getElement().querySelector('.aircraft-icon');
            if (currentIcon) currentIcon.style.color = '#ff4500'; // Resaltar el marcador actual

            selectedFlight = icao24; // Actualizar el avión seleccionado
            found = true;
        }
    });

    if (!found) {
        alert('No se encontró ningún avión con ese ICAO24 o Callsign.');
    }
}

function filterSearchResults() {
    const searchValue = document.getElementById('flight-search').value.trim().toLowerCase();
    const resultsContainer = document.getElementById('search-results');
    resultsContainer.innerHTML = ''; // Limpiar resultados previos

    if (!searchValue) {
        resultsContainer.style.display = 'none';
        return;
    }

    const results = [];
    Object.keys(markers).forEach(icao24 => {
        const flight = markers[icao24].flightData;
        const callsign = flight[1] ? flight[1].trim().toLowerCase() : '';

        if (icao24.toLowerCase().includes(searchValue) || callsign.includes(searchValue)) {
            results.push({ icao24, callsign });
        }
    });

    if (results.length > 0) {
        resultsContainer.style.display = 'block';
        results.forEach(result => {
            const resultDiv = document.createElement('div');
            resultDiv.textContent = `${result.callsign || 'N/A'} (${result.icao24.toUpperCase()})`;
            resultDiv.onclick = () => {
                document.getElementById('flight-search').value = result.icao24; // Rellenar el campo de búsqueda
                resultsContainer.style.display = 'none'; // Ocultar resultados
                searchFlight(); // Realizar la búsqueda
            };
            resultsContainer.appendChild(resultDiv);
        });
    } else {
        resultsContainer.style.display = 'none';
    }
}

// Añadir detección de touch para móviles
document.addEventListener('DOMContentLoaded', function () {
    // Mejorar la interacción táctil
    if ('ontouchstart' in window) {
        document.querySelectorAll('.aircraft-marker').forEach(marker => {
            marker.style.touchAction = 'manipulation';
        });
    }
});
