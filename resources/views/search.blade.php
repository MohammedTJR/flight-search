<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <!-- Imagen superior -->
        <div class="text-center mb-4">
            <!-- Asegúrate de que la ruta de la imagen sea correcta -->
            <img src="{{ asset('img/banner.jpg') }}" class="img-fluid" alt="FlyLow Banner">
        </div>

        <h1 class="text-center">Buscar Vuelos</h1>
        <div class="card shadow p-4">
            <form action="/flights" method="GET">

                <!-- Tipo de viaje -->
                <div class="mb-3">
                    <label class="form-label">Tipo de viaje:</label>
                    <div class="btn-group w-100" role="group" aria-label="Tipo de viaje">
                        <input type="radio" class="btn-check" name="trip_type" id="round_trip" value="1" checked>
                        <label class="btn btn-outline-primary w-100" for="round_trip">Ida y vuelta</label>

                        <input type="radio" class="btn-check" name="trip_type" id="one_way" value="2">
                        <label class="btn btn-outline-primary w-100" for="one_way">Solo ida</label>
                    </div>
                </div>

                <!-- Desde -->
                <div class="mb-3">
                    <label class="form-label">Desde:</label>
                    <select id="departure" name="departure" class="form-select" required>
                        <option value="">Selecciona un aeropuerto...</option>
                    </select>
                </div>

                <!-- Hasta -->
                <div class="mb-3">
                    <label class="form-label">Hasta:</label>
                    <select id="arrival" name="arrival" class="form-select" required>
                        <option value="">Selecciona un aeropuerto...</option>
                    </select>
                </div>

                <!-- Fecha de salida -->
                <div class="mb-3">
                    <label class="form-label">Fecha de salida:</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <!-- Fecha de regreso (ocultada si es solo ida) -->
                <div class="mb-3" id="return_date_container">
                    <label class="form-label">Fecha de regreso:</label>
                    <input type="date" name="return_date" id="return_date" class="form-control">
                </div>

                <!-- Pasajeros -->
                <div class="mb-3">
                    <label class="form-label">Pasajeros:</label>
                    <select name="passengers" class="form-select" required>
                        <option value="">Selecciona el número de pasajeros...</option>
                        <option value="1">1 Pasajero</option>
                        <option value="2">2 Pasajeros</option>
                        <option value="3">3 Pasajeros</option>
                        <option value="4">4 Pasajeros</option>
                        <option value="5">5 Pasajeros</option>
                        <option value="6">6 Pasajeros</option>
                        <option value="7">7 Pasajeros</option>
                        <option value="8">8 Pasajeros</option>
                        <option value="9">9 Pasajeros</option>
                    </select>
                </div>

                <!-- Clase -->
                <div class="mb-3">
                    <label class="form-label">Clase:</label>
                    <select name="class" class="form-select" required>
                        <option value="">Selecciona una clase...</option>
                        <option value="economy">Económica</option>
                        <option value="business">Business</option>
                        <option value="first_class">Primera clase</option>
                    </select>
                </div>

                <!-- Botón de búsqueda -->
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Cargar aeropuertos desde Laravel
            $.getJSON('/airports', function (data) {
                let departureSelect = $('#departure');
                let arrivalSelect = $('#arrival');

                Object.keys(data).forEach(city_country => {
                    let optgroup = $('<optgroup>', { label: city_country });

                    data[city_country].forEach(airport => {
                        let option = new Option(`${airport.name} (${airport.iata})`, airport.iata, false, false);
                        optgroup.append(option);
                    });

                    departureSelect.append(optgroup.clone());
                    arrivalSelect.append(optgroup.clone());
                });

                $('#departure, #arrival').select2({
                    placeholder: "Escribe una ciudad o aeropuerto...",
                    allowClear: true,
                    width: '100%'
                });
            });

            // Cambiar la visibilidad de la fecha de regreso
            $('input[name="trip_type"]').change(function () {
                if ($('#one_way').is(':checked')) {
                    $('#return_date_container').hide();
                    $('#return_date').prop('disabled', true).val('');
                } else {
                    $('#return_date_container').show();
                    $('#return_date').prop('disabled', false);
                }
            });
        });
    </script>

</body>
</html>
