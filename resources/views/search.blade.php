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
        <div class="text-center mb-4">
            <img src="{{ asset('img/banner.jpg') }}" class="img-fluid" alt="FlyLow Banner">
        </div>

        <h1 class="text-center">Buscar Vuelos</h1>
        <div class="card shadow p-4">
            <form action="/flights" method="GET" id="flight-form">

                <!-- Tipo de viaje -->
                <div class="mb-3">
                    <label class="form-label">Tipo de viaje:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="tipo_viaje" id="ida_vuelta" value="ida_vuelta" checked>
                        <label class="btn btn-outline-primary w-50" for="ida_vuelta">Ida y vuelta</label>

                        <input type="radio" class="btn-check" name="tipo_viaje" id="solo_ida" value="solo_ida">
                        <label class="btn btn-outline-primary w-50" for="solo_ida">Solo ida</label>
                    </div>
                </div>

                <!-- Desde -->
                <div class="mb-3">
                    <label class="form-label">Origen:</label>
                    <select id="origen" name="origen" class="form-select" required>
                        <option value="">Selecciona un aeropuerto...</option>
                    </select>
                </div>

                <!-- Hasta -->
                <div class="mb-3">
                    <label class="form-label">Destino:</label>
                    <select id="destino" name="destino" class="form-select" required>
                        <option value="">Selecciona un aeropuerto...</option>
                    </select>
                </div>

                <!-- Fecha de salida -->
                <div class="mb-3">
                    <label class="form-label">Fecha de salida:</label>
                    <input type="date" name="fecha_salida" class="form-control" required>
                </div>

                <!-- Fecha de regreso -->
                <div class="mb-3" id="fecha_regreso_contenedor">
                    <label class="form-label">Fecha de regreso:</label>
                    <input type="date" name="fecha_regreso" id="fecha_regreso" class="form-control">
                </div>

                <!-- Pasajeros -->
                <div class="mb-3">
                    <label class="form-label">Pasajeros:</label>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Adultos (12+ años)</label>
                            <select id="adultos" name="adultos" class="form-select" required>
                                @for ($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }} Adulto(s)</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Niños (2-11 años)</label>
                            <select id="ninos" name="ninos" class="form-select">
                                @for ($i = 0; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }} Niño(s)</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Bebés (con asiento, 0-1 año)</label>
                            <select id="bebes_asiento" name="bebes_asiento" class="form-select">
                                @for ($i = 0; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Bebés (en regazo, 0-1 año)</label>
                            <select id="bebes_regazo" name="bebes_regazo" class="form-select">
                                @for ($i = 0; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Clase -->
                <div class="mb-3">
                    <label class="form-label">Clase:</label>
                    <select name="clase" class="form-select" required>
                        <option value="">Selecciona una clase...</option>
                        <option value="economica">Económica</option>
                        <option value="business">Business</option>
                        <option value="primera_clase">Primera clase</option>
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
                let origenSelect = $('#origen');
                let destinoSelect = $('#destino');

                Object.keys(data).forEach(ciudad => {
                    let optgroup = $('<optgroup>', { label: ciudad });

                    data[ciudad].forEach(aeropuerto => {
                        let option = new Option(`${aeropuerto.name} (${aeropuerto.iata})`, aeropuerto.iata, false, false);
                        optgroup.append(option);
                    });

                    origenSelect.append(optgroup.clone());
                    destinoSelect.append(optgroup.clone());
                });

                $('#origen, #destino').select2({
                    placeholder: "Escribe una ciudad o aeropuerto...",
                    allowClear: true,
                    width: '100%'
                });
            });

            // Ocultar la fecha de regreso si es solo ida
            $('input[name="tipo_viaje"]').change(function () {
                if ($('#solo_ida').is(':checked')) {
                    $('#fecha_regreso_contenedor').hide();
                    $('#fecha_regreso').prop('disabled', true).val('');
                } else {
                    $('#fecha_regreso_contenedor').show();
                    $('#fecha_regreso').prop('disabled', false);
                }
            });

            // Validar pasajeros (no menores solos)
            $('#flight-form').submit(function (e) {
                let adultos = parseInt($('#adultos').val());
                let ninos = parseInt($('#ninos').val());
                let bebes = parseInt($('#bebes_asiento').val()) + parseInt($('#bebes_regazo').val());

                if ((ninos > 0 || bebes > 0) && adultos === 0) {
                    alert("Los niños y bebés deben viajar con al menos un adulto.");
                    e.preventDefault();
                }
            });
        });
    </script>

</body>
</html>
