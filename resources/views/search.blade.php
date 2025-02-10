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
        <h1 class="text-center">Buscar Vuelos</h1>
        <div class="card shadow p-4">
            <form action="/flights" method="GET">
                
                <div class="mb-3">
                    <label class="form-label">Tipo de viaje:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="trip_type" id="round_trip" value="1" checked>
                        <label class="form-check-label" for="round_trip">Ida y vuelta</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="trip_type" id="one_way" value="2">
                        <label class="form-check-label" for="one_way">Solo ida</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Desde:</label>
                    <select id="departure" name="departure" class="form-select" required>
                        <option value="">Selecciona un aeropuerto...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hasta:</label>
                    <select id="arrival" name="arrival" class="form-select" required>
                        <option value="">Selecciona un aeropuerto...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha de salida:</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3" id="return_date_container">
                    <label class="form-label">Fecha de regreso:</label>
                    <input type="date" name="return_date" id="return_date" class="form-control">
                </div>

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

            // Habilitar o deshabilitar la fecha de regreso
            $('input[name="trip_type"]').change(function () {
                if ($('#one_way').is(':checked')) {
                    $('#return_date').prop('disabled', true).val('');
                } else {
                    $('#return_date').prop('disabled', false);
                }
            });
        });
    </script>

</body>
</html>
