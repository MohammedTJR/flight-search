<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
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
                    <div class="col-md-6">
                        <label class="form-label">Origen:</label>
                        <select id="departure" name="departure" class="form-select" required>
                            <option value="">Selecciona un aeropuerto...</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Destino:</label>
                        <select id="arrival" name="arrival" class="form-select" required>
                            <option value="">Selecciona un aeropuerto...</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de salida:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-6" id="return_date_contenedor">
                        <label class="form-label">Fecha de regreso:</label>
                        <input type="date" name="return_date" id="return_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Clase:</label>
                        <select name="travel_class" class="form-select" required>
                            <option value="">Selecciona una clase...</option>
                            <option value="1">Económica</option>
                            <option value="2">Económica Premium</option>
                            <option value="3">Business</option>
                            <option value="4">Primera clase</option>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        jQuery.noConflict();
        (function($) {
            $(document).ready(function() {
                $.getJSON('/airports', function(data) {
                    var departureSelect = $('#departure');
                    var arrivalSelect = $('#arrival');

                    Object.keys(data).forEach(function(ciudad) {
                        var optgroup = $('<optgroup>', {
                            label: ciudad
                        });
                        data[ciudad].forEach(function(aeropuerto) {
                            var option = new Option(aeropuerto.name + ' (' + aeropuerto
                                .iata + ')', aeropuerto.iata, false, false);
                            optgroup.append(option);
                        });
                        departureSelect.append(optgroup.clone());
                        arrivalSelect.append(optgroup.clone());
                    });

                    $('#departure, #arrival').select2({
                        placeholder: "Escribe una ciudad o aeropuerto...",
                        allowClear: true,
                        width: '100%',
                        minimumInputLength: 2,
                        dropdownAutoWidth: true,
                        templateResult: function(data) {
                            return data.text;
                        }
                    });
                });

                let today = new Date().toISOString().split('T')[0];
                $('input[name="date"]').attr('min', today);

                $('input[name="date"]').change(function() {
                    let departureDate = $(this).val();
                    $('input[name="return_date"]').attr('min', departureDate);
                });

                $('input[name="trip_type"]').change(function() {
                    if ($('#solo_ida').is(':checked')) {
                        $('#return_date_contenedor').hide();
                        $('#return_date').prop('disabled', true).val('');
                    } else {
                        $('#return_date_contenedor').show();
                        $('#return_date').prop('disabled', false);
                    }
                });

                $('#flight-form').submit(function(e) {
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
