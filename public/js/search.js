jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
        $.getJSON('/airports', function (data) {
            var departureDropdown = $('#departure-dropdown');
            var arrivalDropdown = $('#arrival-dropdown');

            departureDropdown.empty();
            arrivalDropdown.empty();

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
                    var optionDeparture = $('<div>').text(aeropuerto.name +
                        ' ,' + ciudad + ', ' + aeropuerto.iata).data('iata',
                            aeropuerto.iata);
                    departureDropdown.append(optionDeparture);

                    var optionArrival = $('<div>').text(aeropuerto.name + ' ,' +
                        ciudad + ', ' + aeropuerto.iata).data('iata',
                            aeropuerto.iata);
                    arrivalDropdown.append(optionArrival);
                });
            });

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var userLat = position.coords.latitude;
                        var userLon = position.coords.longitude;

                        var closestAirport = getClosestAirport(userLat, userLon,
                            airports);

                        $('#departure').val(closestAirport.name + ' (' + closestAirport
                            .city + ', ' + closestAirport.iata + ')');
                        $("input[name='departure']").val(closestAirport.iata);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Geolocalización no es soportada por este navegador.',
                    });                }
            }

            function getDistance(lat1, lon1, lat2, lon2) {
                var R = 6371;
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

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
                });                e.preventDefault();
            }
        });
    });
})(jQuery);