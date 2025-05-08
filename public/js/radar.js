// Variables globales
let map;
let baseLayers = {};
let markers = {};
let flightPaths = {}; // Para almacenar trayectorias
let selectedFlight = null;
let updateTimer;
let countdown = 60;
let animationFrame; // Para manejar la animación
let lastUpdateTime = 0;

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
    console.log('Inicializando mapa...'); // Verifica que se ejecuta
    
    map = L.map('map').setView([40.416775, -3.703790], 6);
    
    // Capa por defecto
    mapLayers.osm.addTo(map);
    
    // Controlador del botón - versión más robusta
    const toggleBtn = document.getElementById('toggle-map-style');
    const optionsPanel = document.getElementById('map-style-options');
    
    if (!toggleBtn || !optionsPanel) {
        console.error('Elementos del control de mapa no encontrados!');
        return;
    }
    
    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        console.log('Botón clickeado');
        optionsPanel.classList.toggle('show');
    });
    
    // Cierra al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!optionsPanel.contains(e.target) && e.target !== toggleBtn) {
            optionsPanel.classList.remove('show');
        }
    });
    
    // Selección de capa
    document.querySelectorAll('.map-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            const selectedLayer = this.dataset.layer;
            console.log('Capa seleccionada:', selectedLayer);
            
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
    
    console.log('Mapa y controles inicializados correctamente');
}

// Inicializar el mapa
document.addEventListener('DOMContentLoaded', function () {
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
            // Cálculos optimizados
            const distanceDeg = velocity * deltaTime / 111320;
            const headingRad = ((360 - heading + 90) % 360) * (Math.PI / 180);
            const cosHeading = Math.cos(headingRad);
            const sinHeading = Math.sin(headingRad);
            const cosLat = Math.cos(marker.getLatLng().lat * (Math.PI / 180));

            const newLat = marker.getLatLng().lat + distanceDeg * cosHeading;
            const newLng = marker.getLatLng().lng + distanceDeg * sinHeading / (cosLat || 1); // Evitar división por 0

            marker.setLatLng([newLat, newLng]);

            // Actualizar datos de posición
            flight[5] = newLng;
            flight[6] = newLat;
        }
    }

    animationFrame = requestAnimationFrame(animateFlights);
}

// Modificar fetchFlights para limpiar la animación previa
function fetchFlights() {
    if (Object.keys(markers).length === 0) {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    // Cancelar animación previa para evitar acumulación
    if (animationFrame) {
        cancelAnimationFrame(animationFrame);
    }

    fetch('/api/radar')
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.error) throw new Error(data.error);
            updateFlights(data.states || []);
            updateStatistics(data.states || []);
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
                // Solo actualizar posición directamente si no hay velocidad o está en tierra
                if (!velocity || flight[8]) {
                    markers[icao24].setLatLng([latitude, longitude]);
                }

                // Actualizar datos de vuelo
                markers[icao24].flightData = flight;

                // Rotación
                if (trueTrack !== null) {
                    const iconElement = markers[icao24].getElement();
                    if (iconElement) {
                        const planeIcon = iconElement.querySelector('.aircraft-icon');
                        if (planeIcon) {
                            planeIcon.style.transform = `rotate(${trueTrack}deg)`;
                        }
                    }
                }
            } else {
                // Crear nuevo marcador
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
                            planeIcon.style.transform = `rotate(${trueTrack}deg)`;
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

                // Inicializar trayectoria
                flightPaths[icao24] = {
                    positions: [[latitude, longitude]],
                    timestamp: Date.now()
                };
            }
        }
    });

    // Eliminar marcadores inactivos
    Object.keys(markers).forEach(icao24 => {
        if (!activeFlights.has(icao24)) {
            map.removeLayer(markers[icao24]);
            delete markers[icao24];
            delete flightPaths[icao24];

            if (selectedFlight === icao24) {
                closeFlightDetails();
                selectedFlight = null;
            }
        }
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

    // Interpretar categoría de aeronave
    let category = 'Desconocida';
    const categoryCode = flight[17];
    if (categoryCode !== null) {
        switch (categoryCode) {
            case 0: category = 'Sin información'; break;
            case 1: category = 'Sin información de categoría ADS-B'; break;
            case 2: category = 'Ligero (< 15500 lbs)'; break;
            case 3: category = 'Pequeño (15500 a 75000 lbs)'; break;
            case 4: category = 'Grande (75000 a 300000 lbs)'; break;
            case 5: category = 'Gran Vórtice (tipo B-757)'; break;
            case 6: category = 'Pesado (> 300000 lbs)'; break;
            case 7: category = 'Alto Rendimiento (> 5g y 400 kts)'; break;
            case 8: category = 'Helicóptero'; break;
            case 9: category = 'Planeador'; break;
            case 10: category = 'Más ligero que el aire'; break;
            case 11: category = 'Paracaidista'; break;
            case 12: category = 'Ultraligero/Parapente'; break;
            case 14: category = 'Vehículo Aéreo No Tripulado'; break;
            case 15: category = 'Vehículo espacial/transatmosférico'; break;
            case 16: category = 'Vehículo de Emergencia'; break;
            case 17: category = 'Vehículo de Servicio'; break;
            case 18: category = 'Obstáculo Puntual'; break;
            case 19: category = 'Obstáculo en Grupo'; break;
            case 20: category = 'Obstáculo Lineal'; break;
            default: category = `Código: ${categoryCode}`; break;
        }
    }

    // Crear contenido HTML para el panel
    const detailsHTML = `
            <div class="flight-title">
                <h4><i class="fas fa-plane me-2"></i>${callsign}</h4>
                <p class="mb-0 text-muted">${icao24.toUpperCase()} - ${originCountry}</p>
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
                    <div class="detail-label">Categoría:</div>
                    <div class="detail-value">${category}</div>
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
}

function closeFlightDetails() {
    document.getElementById('flight-details-panel').classList.remove('show');

    // Restaurar color del marcador seleccionado
    if (selectedFlight && markers[selectedFlight]) {
        markers[selectedFlight].getElement().querySelector('i').style.color = '#0d6efd';
        selectedFlight = null;
    }
}

function centerOnFlight(icao24) {
    if (markers[icao24]) {
        const position = markers[icao24].getLatLng();
        map.setView(position, 10);
    }
}