<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscar Vuelos</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Select2 CSS -->
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
          <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="dropdown" aria-expanded="false">
            Seleccionar Pasajeros
          </button>
          <div class="dropdown-menu w-100">
            <div class="d-flex p-3">
              <div class="me-3">
                <label>Adultos (12+ años)</label>
                <select id="adultos" name="adultos" class="form-select">
                  @for ($i = 1; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }} Adulto(s)</option>
                  @endfor
                </select>
              </div>
              <div class="me-3">
                <label>Niños (2-11 años)</label>
                <select id="ninos" name="ninos" class="form-select">
                  @for ($i = 0; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }} Niño(s)</option>
                  @endfor
                </select>
              </div>
              <div class="me-3">
                <label>Bebés (con asiento, 0-1 año)</label>
                <select id="bebes_asiento" name="bebes_asiento" class="form-select">
                  @for ($i = 0; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                  @endfor
                </select>
              </div>
              <div class="me-3">
                <label>Bebés (en regazo, 0-1 año)</label>
                <select id="bebes_regazo" name="bebes_regazo" class="form-select">
                  @for ($i = 0; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }} Bebé(s)</option>
                  @endfor
                </select>
              </div>
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

  <!-- Scripts al final del body para garantizar la carga correcta -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Evitar conflictos con otras librerías que puedan usar $
    jQuery.noConflict();
    (function($) {
      $(document).ready(function() {
        // Cargar aeropuertos desde Laravel
        $.getJSON('/airports', function(data) {
          var origenSelect = $('#origen');
          var destinoSelect = $('#destino');

          Object.keys(data).forEach(function(ciudad) {
            var optgroup = $('<optgroup>', { label: ciudad });
            data[ciudad].forEach(function(aeropuerto) {
              var option = new Option(aeropuerto.name + ' (' + aeropuerto.iata + ')', aeropuerto.iata, false, false);
              optgroup.append(option);
            });
            origenSelect.append(optgroup.clone());
            destinoSelect.append(optgroup.clone());
          });

          // Inicializar select2 en los campos de aeropuerto
          $('#origen, #destino').select2({
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

        // Ocultar la fecha de regreso si se selecciona "Solo ida"
        $('input[name="tipo_viaje"]').change(function() {
          if ($('#solo_ida').is(':checked')) {
            $('#fecha_regreso_contenedor').hide();
            $('#fecha_regreso').prop('disabled', true).val('');
          } else {
            $('#fecha_regreso_contenedor').show();
            $('#fecha_regreso').prop('disabled', false);
          }
        });

        // Validar que el total de pasajeros no supere 9
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
