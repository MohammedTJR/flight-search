<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Resultados de Vuelos</h1>
        <a href="/" class="btn btn-secondary mb-4">Nueva búsqueda</a>

        @if(empty($flights))
            <div class="alert alert-warning">No se encontraron vuelos.</div>
        @else
                <div class="row">
                    @foreach($flights as $flight)
                                <div class="col-md-6">
                                    <div class="card mb-4 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="Logo" width="30">
                                                {{ $flight['flights'][0]['airline'] }}
                                            </h5>
                                            <p class="card-text">
                                                <strong>Precio:</strong> €{{ $flight['price'] }} <br>
                                                <strong>Duración total:</strong>
                                                <?php
                            $totalMinutes = $flight['total_duration'];
                            $hours = floor($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                            echo "$hours h $minutes min";
                        ?>
                                                <br>
                                                <strong>Escalas:</strong>
                                                @if(isset($flight['layovers']) && count($flight['layovers']) > 0)
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
                    @endforeach
                </div>
        @endif
    </div>
</body>

</html>
