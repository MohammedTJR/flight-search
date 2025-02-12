<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .autocomplete-dropdown {
            position: absolute;
            z-index: 9999;
            background-color: #fff;
            border: 1px solid #ccc;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }

        .autocomplete-dropdown div {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-dropdown div:hover {
            background-color: #f0f0f0;
        }

        /* Estilo para el contenedor de los inputs */
        .input-container {
            position: relative;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="text-center mb-4">
            <img src="{{ asset('img/banner.jpg') }}" class="img-fluid" alt="FlyLow Banner">
        </div>

        <h1 class="text-center">Buscar Vuelos</h1>
        <div class="card shadow p-4">
            <form action="/flights" method="GET" id="flight-form">
                <div class="mb-3">
                    <label class="form-label">Tipo de viaje:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="trip_type" id="ida_vuelta" value="1" checked>
                        <label class="btn btn-outline-primary w-50" for="ida_vuelta">Ida y vuelta</label>

                        <input type="radio" class="btn-check" name="trip_type" id="solo_ida" value="2">
                        <label class="btn btn-outline-primary w-50" for="solo_ida">Solo ida</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 input-container">
                        <label class="form-label">Origen:</label>
                        <input type="text" id="departure" name="departure" class="form-control" required
                            autocomplete="off">
                        <div id="departure-dropdown" class="autocomplete-dropdown"></div>
                    </div>

                    <div class="col-md-6 input-container">
                        <label class="form-label">Destino:</label>
                        <input type="text" id="arrival" name="arrival" class="form-control" required autocomplete="off">
                        <div id="arrival-dropdown" class="autocomplete-dropdown"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de salida:</label>
                        <input type="date" name="date" id="departure_date" class="form-control" required>
                    </div>

                    <div class="col-md-6" id="return_date_contenedor">
                        <label class="form-label">Fecha de regreso:</label>
                        <input type="date" name="return_date" id="return_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Clase:</label>
                        <select name="travel_class" class="form-select">
                            <option value="1">Selecciona una clase...</option>
                            <option value="1">Económica</option>
                            <option value="2">Económica Premium</option>
                            <option value="3">Business</option>
                            <option value="4">Primera clase</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                    <label class="form-label">Número de escalas:</label>
                    <select name="stops" class="form-select">
                        <option value="0">Cualquier número de escalas</option>
                        <option value="1">Solo vuelos directos</option>
                        <option value="2">Máximo 1 escala</option>
                        <option value="3">Máximo 2 escalas</option>
                    </select>
                </div>

                    <div class="col-md-6">
                        <label class="form-label">Pasajeros:</label>
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Seleccionar Pasajeros
                        </button>
                        <div class="dropdown-menu w-100">
                            <div class="d-flex p-3">
                                <div class="me-3">
                                    <label>Adultos (12+ años)</label>
                                    <select id="adultos" name="adults" class="form-select">
                                        @for ($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Adulto(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="me-3">
                                    <label>Niños (2-11 años)</label>
                                    <select id="ninos" name="children" class="form-select">
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Niño(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="me-3">
                                    <label>Bebés (con asiento, 0-1 año)</label>
                                    <select id="bebes_asiento" name="infants_in_seat" class="form-select">
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="me-3">
                                    <label>Bebés (en regazo, 0-1 año)</label>
                                    <select id="bebes_regazo" name="infants_on_lap" class="form-select">
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        jQuery.noConflict();
        (function ($) {
            $(document).ready(function () {
                // Cargar los aeropuertos de la API para "Origen" y "Destino"
                $.getJSON('/airports', function (data) {
                    var departureDropdown = $('#departure-dropdown');
                    var arrivalDropdown = $('#arrival-dropdown');

                    // Limpiar los dropdowns primero
                    departureDropdown.empty();
                    arrivalDropdown.empty();

                    // Variables para almacenar la información de los aeropuertos y sus coordenadas
                    var airports = [];
                    Object.keys(data).forEach(function (ciudad) {
                        data[ciudad].forEach(function (aeropuerto) {
                            airports.push({
                                name: aeropuerto.name,
                                city: ciudad,
                                iata: aeropuerto.iata,
                                lat: aeropuerto.latitude,
                                lon: aeropuerto.longitude
                            });
                            // Crear las opciones para el campo de Origen
                            var optionDeparture = $('<div>').text(aeropuerto.name + ' ,' + ciudad + ', ' + aeropuerto.iata).data('iata', aeropuerto.iata);
                            departureDropdown.append(optionDeparture);

                            // Crear las opciones para el campo de Destino
                            var optionArrival = $('<div>').text(aeropuerto.name + ' ,' + ciudad + ', ' + aeropuerto.iata).data('iata', aeropuerto.iata);
                            arrivalDropdown.append(optionArrival);
                        });
                    });

                    // Función para obtener la ubicación actual del dispositivo
                    function getLocation() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                var userLat = position.coords.latitude;
                                var userLon = position.coords.longitude;

                                // Calcular la distancia a cada aeropuerto y seleccionar el más cercano
                                var closestAirport = getClosestAirport(userLat, userLon, airports);

                                // Autocompletar el campo de "Origen" con el aeropuerto más cercano
                                $('#departure').val(closestAirport.name + ' (' + closestAirport.city + ', ' + closestAirport.iata + ')');
                                $("input[name='departure']").val(closestAirport.iata);
                            });
                        } else {
                            alert("Geolocalización no es soportada por este navegador.");
                        }
                    }

                    // Función para calcular la distancia entre dos puntos geográficos
                    function getDistance(lat1, lon1, lat2, lon2) {
                        var R = 6371; // Radio de la Tierra en km
                        var dLat = (lat2 - lat1) * Math.PI / 180;
                        var dLon = (lon2 - lon1) * Math.PI / 180;
                        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                            Math.sin(dLon / 2) * Math.sin(dLon / 2);
                        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        return R * c; // Distancia en km
                    }

                    // Función para obtener el aeropuerto más cercano
                    function getClosestAirport(userLat, userLon, airports) {
                        var closest = airports[0];
                        var closestDist = getDistance(userLat, userLon, closest.lat, closest.lon);

                        airports.forEach(function (airport) {
                            var dist = getDistance(userLat, userLon, airport.lat, airport.lon);
                            if (dist < closestDist) {
                                closest = airport;
                                closestDist = dist;
                            }
                        });

                        return closest;
                    }

                    // Llamar a la función para obtener la ubicación y seleccionar el aeropuerto más cercano
                    getLocation();
                });

                // Función para mostrar el dropdown cuando el usuario escribe
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

                // Cuando se selecciona una opción
                $(document).on('click', '#departure-dropdown div', function () {
                    var selectedText = $(this).text();
                    var inputId = 'departure';
                    $('#' + inputId).val(selectedText);
                    $('#departure-dropdown').hide();

                    // Asignar el código IATA al input de "Origen"
                    $("input[name='departure']").val($(this).data('iata'));
                });

                $(document).on('click', '#arrival-dropdown div', function () {
                    var selectedText = $(this).text();
                    var inputId = 'arrival';
                    $('#' + inputId).val(selectedText);
                    $('#arrival-dropdown').hide();

                    // Asignar el código IATA al input de "Destino"
                    $("input[name='arrival']").val($(this).data('iata'));
                });

                // Fecha de ida no puede ser antes de hoy
                let today = new Date().toISOString().split('T')[0];
                $('#departure_date').attr('min', today);

                // Fecha de regreso no puede ser antes de la fecha de ida
                $('#departure_date').change(function () {
                    let departureDate = $(this).val();
                    $('#return_date').attr('min', departureDate);
                });

                // Cambiar la visibilidad del campo de regreso según el tipo de viaje
                $('input[name="trip_type"]').change(function () {
                    if ($('#solo_ida').is(':checked')) {
                        $('#return_date_contenedor').hide();
                        $('#return_date').prop('disabled', true).val('');
                    } else {
                        $('#return_date_contenedor').show();
                        $('#return_date').prop('disabled', false);
                    }
                });

                $('#flight-form').submit(function (e) {
                    var adultos = parseInt($('#adultos').val());
                    var ninos = parseInt($('#ninos').val());
                    var bebesAsiento = parseInt($('#bebes_asiento').val());
                    var bebesRegazo = parseInt($('#bebes_regazo').val());
                    var totalPasajeros = adultos + ninos + bebesAsiento + bebesRegazo;
                    if (totalPasajeros > 9) {
                        alert("El número total de pasajeros no puede superar los 9.");
                        e.preventDefault();
                    }
                });
            });
        })(jQuery);
    </script>
</body>

</html>