jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
        let today = new Date().toISOString().split('T')[0];

        $('#return_date_contenedor').hide();
        $('#return_date').prop('disabled', true).val('');
        $('#departure_date').attr('min', today);
        $('#departure_date').closest('.col-md-6').removeClass('col-md-6').addClass('col-md-12');

        $.getJSON('/airports', function (data) {
            var airports = [];
            var departureDropdown = $('#departure-dropdown').empty();
            var arrivalDropdown = $('#arrival-dropdown').empty();

            Object.keys(data).forEach(function (ciudad) {
                data[ciudad].forEach(function (aeropuerto) {
                    airports.push({
                        name: aeropuerto.name,
                        city: ciudad,
                        iata: aeropuerto.iata,
                        lat: aeropuerto.latitude,
                        lon: aeropuerto.longitude
                    });
                    var option = $('<div>').text(`${aeropuerto.name}, ${ciudad}, ${aeropuerto.iata}`).data('iata', aeropuerto.iata);
                    departureDropdown.append(option.clone());
                    arrivalDropdown.append(option);
                });
            });

            getLocation(airports);
        });

        function getLocation(airports) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var closestAirport = getClosestAirport(position.coords.latitude, position.coords.longitude, airports);
                    $('#departure').val(`${closestAirport.name} (${closestAirport.city}, ${closestAirport.iata})`);
                    $("input[name='departure']").val(closestAirport.iata);
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Geolocalización no soportada.' });
            }
        }

        function getClosestAirport(lat, lon, airports) {
            return airports.reduce((closest, airport) => {
                return getDistance(lat, lon, airport.lat, airport.lon) < getDistance(lat, lon, closest.lat, closest.lon) ? airport : closest;
            }, airports[0]);
        }

        function getDistance(lat1, lon1, lat2, lon2) {
            var R = 6371;
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function toggleDropdown(inputSelector, dropdownSelector) {
            $(inputSelector).on('input', function () {
                let searchText = $(this).val().toLowerCase();
                $(dropdownSelector).toggle(searchText.length > 0);
                $(dropdownSelector + ' div').each(function () {
                    $(this).toggle($(this).text().toLowerCase().includes(searchText));
                });
            });
        }

        toggleDropdown('#departure', '#departure-dropdown');
        toggleDropdown('#arrival', '#arrival-dropdown');

        $(document).on('click', '#departure-dropdown div, #arrival-dropdown div', function () {
            var inputId = $(this).parent().attr('id') === 'departure-dropdown' ? 'departure' : 'arrival';
            $('#' + inputId).val($(this).text());
            $("input[name='" + inputId + "']").val($(this).data('iata'));
            $(this).parent().hide();
        });

        $('#departure_date').change(function () {
            let departureDate = $(this).val();
            if (departureDate) {
                $('#return_date').prop('disabled', false).attr('min', departureDate);
            } else {
                $('#return_date').prop('disabled', true).val('');
            }
        });

        $('input[name="trip_type"]').change(function () {
            let isOneWay = $('#solo_ida').is(':checked');

            $('#return_date_contenedor').toggle(!isOneWay);
            $('#return_date').prop('disabled', true).val('');

            if (!isOneWay) {
                $('#return_date').attr('required', 'required');
            } else {
                $('#return_date').removeAttr('required');
            }

            let departureContainer = $('#departure_date').closest('.col-md-6, .col-md-12');
            if (isOneWay) {
                departureContainer.removeClass('col-md-6').addClass('col-md-12');
            } else {
                departureContainer.removeClass('col-md-12').addClass('col-md-6');
            }
        });

        $('#flight-form').submit(function (e) {
            var totalPasajeros = ['#adultos', '#ninos', '#bebes_asiento', '#bebes_regazo']
                .map(id => parseInt($(id).val()) || 0).reduce((a, b) => a + b, 0);

            if (totalPasajeros > 9) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'El número total de pasajeros no puede superar los 9.',
                    confirmButtonText: 'Entendido'
                });
                e.preventDefault();
            }
        });
    });
})(jQuery);
