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
                        // Almacena el código IATA del aeropuerto más cercano en un campo oculto.
                        $("input[name='departure']").val(closestAirport.iata);
                    });
                } else {
                    // Muestra un mensaje de error si la geolocalización no es soportada.

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Geolocalización no es soportada por este navegador.',
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
e
            getLocation();
        });

        $('#departure').on('input', function () {
            var searchText = $(this).val().toLowerCase();

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
            var selectedText = $(this).text();
            var inputId = 'departure';
            $('#' + inputId).val(selectedText);
            $('#departure-dropdown').hide();

            $("input[name='departure']").val($(this).data('iata'));
        });

        $(document).on('click', '#arrival-dropdown div', function () {
            var selectedText = $(this).text();
            var inputId = 'arrival';
            $('#' + inputId).val(selectedText);
            $('#arrival-dropdown').hide();

            $("input[name='arrival']").val($(this).data('iata'));
        });

        let today = new Date().toISOString().split('T')[0];
        $('#departure_date').attr('min', today);

        $('#flight-form').submit(function (e) {
            var adultos = parseInt($('#adultos').val());
            var ninos = parseInt($('#ninos').val());
            var bebesAsiento = parseInt($('#bebes_asiento').val());
            var bebesRegazo = parseInt($('#bebes_regazo').val());
            var totalPasajeros = adultos + ninos + bebesAsiento + bebesRegazo;
            if (totalPasajeros > 9) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'El número total de pasajeros no puede superar los 9.',
                    confirmButtonText: 'Entendido'
                }); e.preventDefault();
            }
        });
    });
})(jQuery);
