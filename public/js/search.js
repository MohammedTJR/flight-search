jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
        // Realiza una solicitud GET para obtener datos de aeropuertos en formato JSON.
        $.getJSON('/airports', function (data) {
            // Selecciona los elementos del DOM para los dropdowns de salida y llegada.
            var departureDropdown = $('#departure-dropdown');
            var arrivalDropdown = $('#arrival-dropdown');
            // Limpia los dropdowns para eliminar cualquier contenido previo.
            departureDropdown.empty();
            arrivalDropdown.empty();
            // Crea un array para almacenar la información de los aeropuertos.
            var airports = [];
            // Itera sobre cada ciudad en los datos recibidos.
            Object.keys(data).forEach(function (ciudad) {
                // Itera sobre cada aeropuerto en la ciudad actual.
                data[ciudad].forEach(function (aeropuerto) {
                    // Almacena la información del aeropuerto en el array.
                    airports.push({
                        name: aeropuerto.name,
                        city: ciudad,
                        iata: aeropuerto.iata,
                        lat: aeropuerto.latitude,
                        lon: aeropuerto.longitude
                    });
                    // Crea una opción para el dropdown de salida.
                    var optionDeparture = $('<div>').text(aeropuerto.name +
                        ' ,' + ciudad + ', ' + aeropuerto.iata).data('iata',
                            aeropuerto.iata); // Almacena el código IATA como dato.
                    departureDropdown.append(optionDeparture);// Añade la opción al dropdown.
                    // Crea una opción para el dropdown de llegada.
                    var optionArrival = $('<div>').text(aeropuerto.name + ' ,' +
                        ciudad + ', ' + aeropuerto.iata).data('iata',
                            aeropuerto.iata);
                    arrivalDropdown.append(optionArrival);
                });
            });
            // Función para obtener la ubicación actual del usuario.
            function getLocation() {
                // Verifica si el navegador soporta geolocalización.
                if (navigator.geolocation) {
                    // Obtiene la posición actual del usuario.
                    navigator.geolocation.getCurrentPosition(function (position) {
                        // Extrae la latitud y longitud del usuario.
                        var userLat = position.coords.latitude;
                        var userLon = position.coords.longitude;
                        // Encuentra el aeropuerto más cercano a la ubicación del usuario.
                        var closestAirport = getClosestAirport(userLat, userLon,
                            airports);
                        // Actualiza el campo de salida con el aeropuerto más cercano.
                        $('#departure').val(closestAirport.name + ' (' + closestAirport
                            .city + ', ' + closestAirport.iata + ')');
                        // Guarda solo el código IATA en el campo oculto (lo que se envía).
                        $('#departure_code').val(closestAirport.iata);
                        syncCodeBadges();
                    });
                } else {
                    // Muestra un mensaje de error si la geolocalización no es soportada.

                    FlyLowDialog.alert({
                        variant: 'danger',
                        title: 'No se puede usar tu ubicación',
                        message: 'Tu navegador no admite la geolocalización. Escribe el aeropuerto de salida a mano.'
                    });
                }
            }
            // Función para calcular la distancia entre dos coordenadas geográficas.
            function getDistance(lat1, lon1, lat2, lon2) {
                var R = 6371; // Radio de la Tierra en kilómetros.
                var dLat = (lat2 - lat1) * Math.PI / 180; // Diferencia de latitud en radianes.
                var dLon = (lon2 - lon1) * Math.PI / 180; // Diferencia de longitud en radianes.
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2); // Fórmula de Haversine.
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); // Distancia angular.
                return R * c; // Distancia en kilómetros.
            }

            // Función para encontrar el aeropuerto más cercano a la ubicación del usuario.
            function getClosestAirport(userLat, userLon, airports) {
                var closest = airports[0];// Inicializa con el primer aeropuerto.
                var closestDist = getDistance(userLat, userLon, closest.lat, closest.lon);

                // Itera sobre todos los aeropuertos para encontrar el más cercano.
                airports.forEach(function (airport) {
                    var dist = getDistance(userLat, userLon, airport.lat, airport.lon);
                    if (dist < closestDist) {
                        closest = airport;// Actualiza el aeropuerto más cercano.
                        closestDist = dist;// Actualiza la distancia más corta.
                    }
                });

                return closest;
            }
            // Ejecuta la función para obtener la ubicación del usuario.
            getLocation();
        });

        // Muestra el código IATA elegido junto a la etiqueta de cada campo.
        function syncCodeBadges() {
            ['departure', 'arrival'].forEach(function (id) {
                var code = $('#' + id + '_code').val();
                $('#' + id + '-badge').text(code).prop('hidden', !code);
            });
        }

        $('#departure').on('input', function () {
            var searchText = $(this).val().toLowerCase();
            // Al teclear se invalida la selección previa: el código se fija solo al elegir de la lista.
            $('#departure_code').val('');
            syncCodeBadges();

            if (searchText.length > 0) {
                $('#departure-dropdown').show();
            } else {
                $('#departure-dropdown').hide();
            }

            $('#departure-dropdown div').each(function () {
                var optionText = $(this).text().toLowerCase();
                if (optionText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#arrival').on('input', function () {
            var searchText = $(this).val().toLowerCase();
            $('#arrival_code').val('');
            syncCodeBadges();

            if (searchText.length > 0) {
                $('#arrival-dropdown').show();
            } else {
                $('#arrival-dropdown').hide();
            }

            $('#arrival-dropdown div').each(function () {
                var optionText = $(this).text().toLowerCase();
                if (optionText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $(document).on('click', '#departure-dropdown div', function () {
            $('#departure').val($(this).text());              // nombre completo (visible)
            $('#departure_code').val($(this).data('iata'));   // solo el código (se envía)
            syncCodeBadges();
            $('#departure-dropdown').hide();
        });

        $(document).on('click', '#arrival-dropdown div', function () {
            $('#arrival').val($(this).text());                // nombre completo (visible)
            $('#arrival_code').val($(this).data('iata'));     // solo el código (se envía)
            syncCodeBadges();
            $('#arrival-dropdown').hide();
        });

        let today = new Date().toISOString().split('T')[0];
        $('#departure_date').attr('min', today);

        let tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        let tomorrowFormatted = tomorrow.toISOString().split('T')[0];

        $('#departure_date').val(tomorrowFormatted);

        // Intercambiar origen y destino (texto visible y código oculto)
        $('#swap-airports').on('click', function () {
            var $d = $('#departure'), $a = $('#arrival');
            var tmpText = $d.val();
            $d.val($a.val());
            $a.val(tmpText);

            var $dc = $('#departure_code'), $ac = $('#arrival_code');
            var tmpCode = $dc.val();
            $dc.val($ac.val());
            $ac.val(tmpCode);
            syncCodeBadges();

            $('#departure-dropdown, #arrival-dropdown').hide();
        });

        $('#flight-form').submit(function (e) {
            // Debe haberse seleccionado origen y destino de la lista (para tener el código IATA).
            if (!$('#departure_code').val() || !$('#arrival_code').val()) {
                e.preventDefault();
                FlyLowDialog.alert({
                    variant: 'warning',
                    title: 'Elige origen y destino',
                    message: 'Selecciona el origen y el destino de la lista de sugerencias para poder buscar.'
                });
                return;
            }

            var adultos = parseInt($('#adultos').val());
            var ninos = parseInt($('#ninos').val());
            var bebesAsiento = parseInt($('#bebes_asiento').val());
            var bebesRegazo = parseInt($('#bebes_regazo').val());
            var totalPasajeros = adultos + ninos + bebesAsiento + bebesRegazo;
            if (totalPasajeros > 9) {
                e.preventDefault();
                FlyLowDialog.alert({
                    variant: 'warning',
                    title: 'Demasiados pasajeros',
                    message: 'El número total de pasajeros no puede superar los 9.'
                });
            }
        });

        function updateQuantity(id, delta, event) {
            // Prevenir que el clic cierre el dropdown
            if (event) {
                event.stopPropagation();
            }

            const input = document.getElementById(id);
            const newValue = parseInt(input.value) + delta;

            if (newValue >= parseInt(input.min) && newValue <= parseInt(input.max)) {
                input.value = newValue;
            }

            // Verificar el total de pasajeros
            const total = getTotalPassengers();
            if (total > 9) {
                input.value = parseInt(input.value) - delta;
                FlyLowToast.warning({
                    title: 'Límite de pasajeros',
                    description: 'El número máximo de pasajeros es 9.'
                });
            }

            refreshPassengerSummary();
        }

        function getTotalPassengers() {
            return parseInt(document.getElementById('adultos').value) +
                parseInt(document.getElementById('ninos').value) +
                parseInt(document.getElementById('bebes_asiento').value) +
                parseInt(document.getElementById('bebes_regazo').value);
        }

        // Actualiza el resumen del botón ("2 pasajeros · Business") y su tooltip detallado.
        function refreshPassengerSummary() {
            const total = getTotalPassengers();
            const adultos = parseInt(document.getElementById('adultos').value);
            const ninos = parseInt(document.getElementById('ninos').value);
            const bebesAsiento = parseInt(document.getElementById('bebes_asiento').value);
            const bebesRegazo = parseInt(document.getElementById('bebes_regazo').value);
            const clase = document.getElementById('travel_class');
            const claseTexto = clase.options[clase.selectedIndex].text;

            document.getElementById('passengers-summary').textContent =
                `${total} ${total === 1 ? 'pasajero' : 'pasajeros'} · ${claseTexto}`;

            let tooltipText = [];
            if (adultos > 0) tooltipText.push(`${adultos} Adulto${adultos !== 1 ? 's' : ''}`);
            if (ninos > 0) tooltipText.push(`${ninos} Niño${ninos !== 1 ? 's' : ''}`);
            if (bebesAsiento > 0) tooltipText.push(`${bebesAsiento} Bebé${bebesAsiento !== 1 ? 's' : ''} con asiento`);
            if (bebesRegazo > 0) tooltipText.push(`${bebesRegazo} Bebé${bebesRegazo !== 1 ? 's' : ''} en regazo`);
            document.querySelector('.pax-trigger').setAttribute('title', tooltipText.join(', '));
        }

        function actualizarPasajeros() {
            refreshPassengerSummary();

            // Cerrar el desplegable (Bootstrap no se expone como global, así que se alterna con su propio botón)
            const trigger = document.querySelector('.pax-trigger');
            if (trigger.classList.contains('show')) {
                trigger.click();
            }
        }

        $('#travel_class').on('change', refreshPassengerSummary);

        // El ancho del desplegable lo controla el CSS (ancho fijo compacto)

        // Asignar las funciones a los botones
        window.updateQuantity = updateQuantity;
        window.actualizarPasajeros = actualizarPasajeros;
    });
})(jQuery);
