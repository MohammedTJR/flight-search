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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Detalles del Vuelo</h1>
            <a href="/" class="btn btn-secondary">Volver</a>
        </div>

        <h3 class="text-center">Vuelo {{ $flight['flights'][0]['flight_number'] ?? 'N/A' }}</h3>

        <div class="row mt-4">
            <div class="col-md-4 text-center">
                @if (!empty($flight['airline_logo']))
                    <img src="{{ $flight['airline_logo'] }}" alt="Logo Aerolínea" class="img-fluid mb-2">
                @endif
                @if (!empty($flight['price']))
                    <p class="fw-bold">Precio: €{{ $flight['price'] }}</p>
                @endif
            </div>
            <div class="col-md-8">
                <h4>Información Principal</h4>
                @foreach ($flight['flights'] as $segmento)
                    <p><strong>Aerolínea:</strong> {{ $segmento['airline'] ?? 'N/A' }}</p>
                    <p><strong>Salida:</strong> {{ $segmento['departure_airport']['name'] ?? 'N/A' }} - {{ $segmento['departure_airport']['id'] ?? '' }} a las {{ !empty($segmento['departure_airport']['time']) ? date('H:i', strtotime($segmento['departure_airport']['time'])) : 'N/A' }}</p>
                    <p><strong>Llegada:</strong> {{ $segmento['arrival_airport']['name'] ?? 'N/A' }} - {{ $segmento['arrival_airport']['id'] ?? '' }} a las {{ !empty($segmento['arrival_airport']['time']) ? date('H:i', strtotime($segmento['arrival_airport']['time'])) : 'N/A' }}</p>
                    <p><strong>Duración:</strong> {{ intdiv($segmento['duration'], 60) }}h {{ $segmento['duration'] % 60 }}min</p>
                    <p><strong>Clase de viaje:</strong> {{ $segmento['travel_class'] ?? 'N/A' }}</p>
                    <p><strong>Espacio para piernas:</strong> {{ $segmento['legroom'] ?? 'N/A' }}</p>
                @endforeach
            </div>
        </div>

        <div class="card shadow p-4 mt-4">
            <h4>Información Adicional</h4>
            <p><strong>Tipo de vuelo:</strong> {{ $flight['type'] ?? 'N/A' }}</p>
            <p><strong>Duración total:</strong> {{ intdiv($flight['total_duration'], 60) }}h {{ $flight['total_duration'] % 60 }}min</p>
        </div>

        <div class="card shadow p-4 mt-4 d-flex align-items-center">
            <div class="me-3">
                <h4>Emisiones de Carbono</h4>
                <p><strong>Este vuelo:</strong> {{ $flight['carbon_emissions']['this_flight'] ?? 'N/A' }}g CO₂</p>
                <p><strong>Promedio en esta ruta:</strong> {{ $flight['carbon_emissions']['typical_for_this_route'] ?? 'N/A' }}g CO₂</p>
                <p><strong>Diferencia:</strong> {{ $flight['carbon_emissions']['difference_percent'] ?? 'N/A' }}%</p>
            </div>
            <img src="https://cdn-icons-png.flaticon.com/512/1684/1684375.png" alt="CO2" width="100">
        </div>
    </div>
</body>
</html>
