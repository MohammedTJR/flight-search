<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Vuelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Detalles del Vuelo</h1>
            <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
        </div>

        <h3 class="text-center mb-4">Vuelo {{ $flight['flights'][0]['flight_number'] ?? 'N/A' }}</h3>

        <div class="row mb-4">
            <div class="col-md-3 text-center">
                <img src="{{ $flight['airline_logo'] ?? '' }}" alt="Logo aerolínea" class="img-fluid" width="100">
                <p class="mt-2"><strong>Precio:</strong> €{{ $flight['price'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-9">
                <p><strong>Aerolínea:</strong> {{ $flight['flights'][0]['airline'] ?? 'N/A' }}</p>
                <p><strong>Tipo de vuelo:</strong> {{ $flight['type'] ?? 'N/A' }}</p>
                <p><strong>Duración total:</strong> {{ intdiv($flight['total_duration'] ?? 0, 60) }}h {{ ($flight['total_duration'] ?? 0) % 60 }}min</p>
            </div>
        </div>

        <div class="card shadow p-4 mb-4">
            <h4>Detalles del Vuelo</h4>
            @foreach ($flight['flights'] as $segmento)
                <div class="mb-3 p-3 border rounded">
                    <h5>{{ $segmento['airline'] ?? 'N/A' }} ({{ $segmento['flight_number'] ?? 'N/A' }})</h5>
                    <p><strong>Salida:</strong> {{ $segmento['departure_airport']['name'] ?? 'N/A' }} ({{ $segmento['departure_airport']['id'] ?? 'N/A' }}) - {{ !empty($segmento['departure_airport']['time']) ? date('H:i', strtotime($segmento['departure_airport']['time'])) : 'N/A' }}</p>
                    <p><strong>Llegada:</strong> {{ $segmento['arrival_airport']['name'] ?? 'N/A' }} ({{ $segmento['arrival_airport']['id'] ?? 'N/A' }}) - {{ !empty($segmento['arrival_airport']['time']) ? date('H:i', strtotime($segmento['arrival_airport']['time'])) : 'N/A' }}</p>
                    <p><strong>Duración:</strong> {{ intdiv($segmento['duration'] ?? 0, 60) }}h {{ ($segmento['duration'] ?? 0) % 60 }}min</p>
                    <p><strong>Clase:</strong> {{ $segmento['travel_class'] ?? 'N/A' }}</p>
                    <p><strong>Avión:</strong> {{ $segmento['airplane'] ?? 'N/A' }}</p>
                    <p><strong>Espacio para piernas:</strong> {{ $segmento['legroom'] ?? 'N/A' }}</p>
                    <p><strong>Extras:</strong> {{ implode(', ', $segmento['extensions'] ?? []) }}</p>
                </div>
            @endforeach
        </div>

        <div class="card shadow p-4 mb-4">
            <h4>Emisiones de CO₂</h4>
            <div class="d-flex align-items-center">
                <div>
                    <p><strong>Este vuelo:</strong> {{ number_format($flight['carbon_emissions']['this_flight'] ?? 0) }}g CO₂</p>
                    <p><strong>Promedio de la ruta:</strong> {{ number_format($flight['carbon_emissions']['typical_for_this_route'] ?? 0) }}g CO₂</p>
                    <p><strong>Diferencia:</strong> {{ $flight['carbon_emissions']['difference_percent'] ?? 0 }}%</p>
                </div>
                <img src="{{ asset('img/carbon-footprint.jpg') }}" alt="CO₂ Emissions" class="ms-4" width="100">
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="#" class="btn btn-primary">Reservar ahora</a>
        </div>
    </div>
</body>
</html>
