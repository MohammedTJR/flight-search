<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Resultados de Vuelos</h1>
        <a href="/" class="btn btn-secondary mb-4">Nueva búsqueda</a>

        @php
            $categorias = [
                'Mejores vuelos' => $flights ?? [],
                'Otros vuelos' => $other_flights ?? []
            ];
        @endphp

        @foreach ($categorias as $titulo => $listaVuelos)
            @if (!empty($listaVuelos))
                <h2 class="mt-4">{{ $titulo }}</h2>
                <div class="row">
                    @foreach ($listaVuelos as $flight)
                        @if(isset($flight['flights'][0])) 
                            @php
                                $detalleVuelo = $flight['flights'][0];
                                $horaSalida = date('H:i', strtotime($detalleVuelo['departure_airport']['time']));
                                $horaLlegada = date('H:i', strtotime($detalleVuelo['arrival_airport']['time']));
                            @endphp
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm">
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
                                            {{ intdiv($flight['total_duration'], 60) }}h {{ $flight['total_duration'] % 60 }}min
                                            <br>
                                            <strong>Escalas:</strong> 
                                            @if(!empty($flight['layovers']))
                                                @foreach($flight['layovers'] as $layover)
                                                    {{ $layover['name'] }} ({{ $layover['duration'] }} min)
                                                @endforeach
                                            @else
                                                Directo (sin escalas)
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">No se encontraron vuelos en la categoría "{{ $titulo }}".</div>
            @endif
        @endforeach
    </div>
</body>
</html>
