<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Vuelos</title>
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
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Resultados de Vuelos</h1>
        <a href="/" class="btn btn-secondary mb-4">Nueva búsqueda</a>

        <div class="card shadow p-4 mt-4">
            <h3 class="text-center">Información de Precios</h3>
            <p><strong>Nivel de precio:</strong> <span id="priceLevel"></span> |
                <strong>Rango típico de precios:</strong> <span id="priceRange"></span> €
            </p>
        </div>

        <div class="card shadow p-4 mt-3">
            <h3 class="text-center">Evolución de Precios</h3>
            <canvas id="priceChart"></canvas>
        </div>

        @php
            $categorias = [
                'Mejores vuelos' => $flights ?? [],
                'Otros vuelos' => $other_flights ?? [],
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
                    <div class="row">
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
                                                        <img src="{{ $detalleVuelo['airline_logo'] }}" alt="Logo" width="30">
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

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const priceData = @json($prices['price_history'] ?? []);
            const priceLevel = @json($prices['price_level'] ?? 'No disponible');
            const priceRange = @json($prices['typical_price_range'] ?? []);

            const level = priceLevel.charAt(0).toUpperCase() + priceLevel.slice(1);

            var color;

            document.getElementById("priceLevel").innerText = level

            if (level === "Low") {
                color = "green";
            } else if (level === "Typical") {
                color = "orange";
            } else if (level === "High") {
                color = "red";
            }


            document.getElementById("priceRange").innerText = priceRange.length === 2 ?
                `${priceRange[0]} - ${priceRange[1]}` : "No disponible";

            if (priceData.length === 0) {
                console.warn("No hay datos de precios.");
                return;
            }

            const labels = priceData.map(entry => {
                const date = new Date(entry[0] * 1000);
                return date.toLocaleDateString();
            });

            const prices = priceData.map(entry => entry[1]);

            const ctx = document.getElementById("priceChart").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Precio Histórico (€)",
                        data: prices,
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
        });
    </script>
</body>

</html>