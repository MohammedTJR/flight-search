<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Vuelos</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .flight-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flight-times {
            font-size: 1.2em;
            font-weight: bold;
        }

        .clickable-card {
            transition: transform 0.2s ease-in-out;
        }

        .clickable-card:hover {
            transform: scale(1.02);
        }

        .price-level-box {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 5px;
            margin-left: 10px;
        }

        .price-chart-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .price-chart-date {
            font-size: 1.2em;
            font-weight: bold;
        }

        .date-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .date-box {
            flex: 1;
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
            margin: 0 5px;
            cursor: pointer;
        }

        .date-box.active {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Resultados de Vuelos</h1>
        <a href="/" class="btn btn-secondary mb-4">Nueva búsqueda</a>

        <!-- Navegación de fechas -->
        <div class="date-navigation">
            <a href="{{ route('flights', [
                'departure' => request('departure'),
                'arrival' => request('arrival'),
                'date' => \Carbon\Carbon::parse(request('date'))->subDay()->format('Y-m-d'),
                'trip_type' => request('trip_type'),
                'adults' => request('adults'),
                'children' => request('children'),
                'infants_in_seat' => request('infants_in_seat'),
                'infants_on_lap' => request('infants_on_lap'),
                'travel_class' => request('travel_class'),
                'stops' => request('stops'),
            ]) }}"
                class="btn btn-primary {{ \Carbon\Carbon::parse(request('date'))->isToday() ? 'disabled' : '' }}"
                id="prevDayBtnNav">← Ayer</a>

            <div class="date-box" id="dateBoxYesterday">
                {{ \Carbon\Carbon::parse(request('date'))->subDay()->format('d/m') }}
            </div>
            <div class="date-box active" id="dateBoxToday">
                {{ \Carbon\Carbon::parse(request('date'))->format('d/m') }}
            </div>
            <div class="date-box" id="dateBoxTomorrow">
                {{ \Carbon\Carbon::parse(request('date'))->addDay()->format('d/m') }}
            </div>

            <a href="{{ route('flights', [
                'departure' => request('departure'),
                'arrival' => request('arrival'),
                'date' => \Carbon\Carbon::parse(request('date'))->addDay()->format('Y-m-d'),
                'trip_type' => request('trip_type'),
                'adults' => request('adults'),
                'children' => request('children'),
                'infants_in_seat' => request('infants_in_seat'),
                'infants_on_lap' => request('infants_on_lap'),
                'travel_class' => request('travel_class'),
                'stops' => request('stops'),
            ]) }}"
                class="btn btn-primary" id="nextDayBtnNav">Mañana →</a>
        </div>

        <!-- Mostrar origen y destino -->
        <div class="text-center mt-3">
            <strong>Origen:</strong> {{ request('departure') }} |
            <strong>Destino:</strong> {{ request('arrival') }}
        </div>

        <div class="card shadow p-4 mt-4">
            <h3 class="text-center">Información de Precios</h3>
            <p>
                <strong>Nivel de precio:</strong> <span id="priceLevel"></span>
                <span id="priceLevelBox" class="price-level-box"></span>
                | <strong>Rango típico de precios:</strong> <span id="priceRange"></span> €
            </p>
        </div>

        @php
            $categorias = [
                'Vuelos más económicos' => $flights ?? [],
                'Vuelos menos económicos' => $other_flights ?? [],
            ];
        @endphp

        @php
            $noHayVuelos = empty($flights) && empty($other_flights);
        @endphp

        @if ($noHayVuelos)
            <div class="alert alert-danger text-center mt-4">No se encontraron vuelos disponibles.</div>
        @else
            @foreach ($categorias as $titulo => $listaVuelos)
                @if (!empty($listaVuelos))
                    <h2 class="mt-4">{{ $titulo }}</h2>
                    <div class="row" id="flightList">
                        @foreach ($listaVuelos as $flight)
                            @if (isset($flight['flights'][0]))
                                @php
                                    $detalleVuelo = $flight['flights'][0];
                                    $horaSalida = date('H:i', strtotime($detalleVuelo['departure_airport']['time']));
                                    $horaLlegada = date('H:i', strtotime($detalleVuelo['arrival_airport']['time']));
                                @endphp
                                <div class="col-md-6">
                                    <a href="{{ route('flight.show', ['id' => $detalleVuelo['flight_number']]) }}"
                                        class="text-decoration-none">
                                        <div class="card mb-4 shadow-sm clickable-card">
                                            <div class="card-body">
                                                <div class="flight-header">
                                                    <h5 class="card-title">
                                                        <img src="{{ $detalleVuelo['airline_logo'] }}" alt="Logo"
                                                            width="30">
                                                        {{ $detalleVuelo['airline'] }}
                                                    </h5>
                                                    <div class="flight-times">
                                                        {{ $horaSalida }} → {{ $horaLlegada }}
                                                    </div>
                                                </div>
                                                <p class="card-text">
                                                    <strong>Precio:</strong> €{{ $flight['price'] }} <br>
                                                    <strong>Duración total:</strong>
                                                    {{ intdiv($flight['total_duration'], 60) }}h
                                                    {{ $flight['total_duration'] % 60 }}min
                                                    <br>
                                                    <strong>Escalas:</strong>
                                                    @if (!empty($flight['layovers']))
                                                        @foreach ($flight['layovers'] as $layover)
                                                            {{ $layover['name'] }} ({{ $layover['duration'] }} min)
                                                        @endforeach
                                                    @else
                                                        Directo (sin escalas)
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
        <div class="card shadow p-4 mt-3">
            <h3 class="text-center">Evolución de Precios</h3>

            <!-- Botones para cambiar día -->
            <div class="price-chart-buttons">
                <button class="btn btn-primary" id="prevDayBtn">Día Anterior</button>
                <button class="btn btn-primary" id="nextDayBtn">Día Siguiente</button>
            </div>

            <!-- Mostrar la fecha seleccionada -->
            <div class="text-center price-chart-date" id="selectedDate">Fecha seleccionada: {{ date('d-m-Y') }}</div>

            <canvas id="priceChart"></canvas>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const priceData = @json($prices['price_history'] ?? []);
            const priceLevel = @json($prices['price_level'] ?? 'No disponible');
            const priceRange = @json($prices['typical_price_range'] ?? []);

            const level = priceLevel.charAt(0).toUpperCase() + priceLevel.slice(1);
            document.getElementById("priceLevel").innerText = level;

            let color;
            if (level === "Low") {
                color = "green";
            } else if (level === "Typical") {
                color = "orange";
            } else if (level === "High") {
                color = "red";
            }

            document.getElementById("priceLevelBox").style.backgroundColor = color;

            document.getElementById("priceRange").innerText = priceRange.length === 2 ?
                `${priceRange[0]} - ${priceRange[1]}` : "No disponible";

            if (priceData.length === 0) {
                console.warn("No hay datos de precios.");
                return;
            }

            let currentIndex = priceData.length - 1; // Empezamos con el último día de los datos
            const labels = priceData.map(entry => {
                const date = new Date(entry[0] * 1000);
                return date.toLocaleDateString();
            });

            const prices = priceData.map(entry => entry[1]);

            const ctx = document.getElementById("priceChart").getContext("2d");
            let chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels.slice(0, currentIndex + 1), // Mostrar solo los días hasta el día actual
                    datasets: [{
                        label: "Precio Histórico (€)",
                        data: prices.slice(0, currentIndex + 1),
                        borderColor: color,
                        backgroundColor: "rgba(0, 0, 255, 0.1)",
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Fecha"
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: "Precio (€)"
                            }
                        }
                    }
                }
            });

            // Funciones para los botones de navegación
            document.getElementById('prevDayBtn').addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateDateLabel();
                }
            });

            document.getElementById('nextDayBtn').addEventListener('click', function() {
                if (currentIndex < priceData.length - 1) {
                    currentIndex++;
                    updateDateLabel();
                }
            });

            function updateDateLabel() {
                const selectedDate = new Date(priceData[currentIndex][0] * 1000);
                document.getElementById('selectedDate').innerText =
                    `Fecha seleccionada: ${selectedDate.toLocaleDateString()}`;
            }
        });

        const dateBoxYesterday = document.getElementById('dateBoxYesterday');
        const dateBoxToday = document.getElementById('dateBoxToday');
        const dateBoxTomorrow = document.getElementById('dateBoxTomorrow');

        const prevDayBtnNav = document.getElementById('prevDayBtnNav');
        const nextDayBtnNav = document.getElementById('nextDayBtnNav');

        let currentDate = new Date();
        let yesterday = new Date(currentDate);
        yesterday.setDate(currentDate.getDate() - 1);
        let tomorrow = new Date(currentDate);
        tomorrow.setDate(currentDate.getDate() + 1);

        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            return `${day}/${month}`;
        }

        function updateDateBoxes() {
            dateBoxYesterday.textContent = formatDate(yesterday);
            dateBoxToday.textContent = formatDate(currentDate);
            dateBoxTomorrow.textContent = formatDate(tomorrow);
        }

        function fetchFlights(date) {
            const departure = document.querySelector('input[name="departure"]').value;
            const arrival = document.querySelector('input[name="arrival"]').value;
            const tripType = document.querySelector('input[name="trip_type"]:checked').value;
            const adults = document.querySelector('select[name="adults"]').value;
            const children = document.querySelector('select[name="children"]').value;
            const infants_in_seat = document.querySelector('select[name="infants_in_seat"]').value;
            const infants_on_lap = document.querySelector('select[name="infants_on_lap"]').value;
            const travel_class = document.querySelector('select[name="travel_class"]').value;
            const stops = document.querySelector('input[name="stops"]:checked').value;

            const url =
                `/flights/search?departure=${departure}&arrival=${arrival}&date=${date}&trip_type=${tripType}&adults=${adults}&children=${children}&infants_in_seat=${infants_in_seat}&infants_on_lap=${infants_on_lap}&travel_class=${travel_class}&stops=${stops}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Actualizar la vista con los nuevos vuelos
                    updateFlightList(data.flights);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateFlightList(flights) {
            const flightList = document.getElementById('flightList');
            flightList.innerHTML = ''; // Limpiar la lista actual

            flights.forEach(flight => {
                if (flight.flights && flight.flights[0]) {
                    const detalleVuelo = flight.flights[0];
                    const horaSalida = new Date(detalleVuelo.departure_airport.time).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    const horaLlegada = new Date(detalleVuelo.arrival_airport.time).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const flightElement = document.createElement('div');
                    flightElement.className = 'col-md-6';
                    flightElement.innerHTML = `
                        <a href="/flight/${detalleVuelo.flight_number}" class="text-decoration-none">
                            <div class="card mb-4 shadow-sm clickable-card">
                                <div class="card-body">
                                    <div class="flight-header">
                                        <h5 class="card-title">
                                            <img src="${detalleVuelo.airline_logo}" alt="Logo" width="30">
                                            ${detalleVuelo.airline}
                                        </h5>
                                        <div class="flight-times">
                                            ${horaSalida} → ${horaLlegada}
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        <strong>Precio:</strong> €${flight.price} <br>
                                        <strong>Duración total:</strong>
                                        ${Math.floor(flight.total_duration / 60)}h ${flight.total_duration % 60}min
                                        <br>
                                        <strong>Escalas:</strong>
                                        ${flight.layovers && flight.layovers.length > 0 ? flight.layovers.map(layover => `${layover.name} (${layover.duration} min)`).join(', ') : 'Directo (sin escalas)'}
                                    </p>
                                </div>
                            </div>
                        </a>
                    `;
                    flightList.appendChild(flightElement);
                }
            });
        }

        prevDayBtnNav.addEventListener('click', function() {
            currentDate.setDate(currentDate.getDate() - 1);
            yesterday.setDate(yesterday.getDate() - 1);
            tomorrow.setDate(tomorrow.getDate() - 1);
            updateDateBoxes();
            fetchFlights(currentDate.toISOString().split('T')[0]);
        });

        nextDayBtnNav.addEventListener('click', function() {
            currentDate.setDate(currentDate.getDate() + 1);
            yesterday.setDate(yesterday.getDate() + 1);
            tomorrow.setDate(tomorrow.getDate() + 1);
            updateDateBoxes();
            fetchFlights(currentDate.toISOString().split('T')[0]);
        });

        updateDateBoxes();
    </script>
</body>

</html>
