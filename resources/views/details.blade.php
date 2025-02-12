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
        <h1 class="text-center">Detalles del Vuelo</h1>
        <a href="/" class="btn btn-secondary mb-4">Volver a la búsqueda</a>

        <div class="card shadow p-4">
            <h3 class="text-center">
                <img src="{{ $flight['airline_logo'] }}" alt="Logo" width="50">
            </h3>
            <p class="text-center"><strong>Precio:</strong> €{{ $flight['price'] }}</p>
        </div>

        <div class="card shadow p-4 mt-3">
            <h4>Información del vuelo</h4>
            @foreach ($flight['flights'] as $segmento)
                <div class="mb-3 p-3 border rounded">
                    <h5>{{ $segmento['airline'] }} ({{ $segmento['flight_number'] }})</h5>
                    <p><strong>Salida:</strong> {{ $segmento['departure_airport']['name'] }}
                        ({{ $segmento['departure_airport']['id'] }}) -
                        {{ date('H:i', strtotime($segmento['departure_airport']['time'])) }}
                    </p>
                    <p><strong>Llegada:</strong> {{ $segmento['arrival_airport']['name'] }}
                        ({{ $segmento['arrival_airport']['id'] }}) -
                        {{ date('H:i', strtotime($segmento['arrival_airport']['time'])) }}
                    </p>
                    <p><strong>Duración:</strong> {{ intdiv($segmento['duration'], 60) }}h
                        {{ $segmento['duration'] % 60 }}min
                    </p>
                    <p><strong>Clase de viaje:</strong> {{ $segmento['travel_class'] }}</p>
                    <p><strong>Espacio para las piernas:</strong> {{ $segmento['legroom'] }}</p>
                </div>
            @endforeach
        </div>

        @if (!empty($flight['layovers']))
            <div class="card shadow p-4 mt-3">
                <h4>Escalas</h4>
                @foreach ($flight['layovers'] as $layover)
                    <p><strong>{{ $layover['name'] }} ({{ $layover['id'] }})</strong> - {{ intdiv($layover['duration'], 60) }}h
                        {{ $layover['duration'] % 60 }}min
                    </p>
                @endforeach
            </div>
        @endif

        <div class="card shadow p-4 mt-3">
            <h4>Información Adicional</h4>
            <p><strong>Tipo de vuelo:</strong> {{ $flight['type'] }}</p>
            <p><strong>Duración total (incluyendo escalas):</strong> {{ intdiv($flight['total_duration'], 60) }}h
                {{ $flight['total_duration'] % 60 }}min
            </p>

            <h5 class="mt-3">Emisiones de Carbono</h5>
            <p><strong>Este vuelo:</strong> {{ $flight['carbon_emissions']['this_flight'] }}g CO₂</p>
            <p><strong>Típico para esta ruta:</strong> {{ $flight['carbon_emissions']['typical_for_this_route'] }}g CO₂
            </p>
            <p><strong>Diferencia:</strong> {{ $flight['carbon_emissions']['difference_percent'] }}%</p>

            @if (!empty($flight['flights'][0]['often_delayed_by_over_30_min']))
                <span class="badge bg-danger">Este vuelo suele retrasarse más de 30 minutos</span>
            @endif
        </div>

        @if (!empty($flight['extensions']))
            <div class="card shadow p-4 mt-3">
                <h4>Extras del Vuelo</h4>
                <ul>
                    @foreach ($flight['extensions'] as $extension)
                        <li>{{ $extension }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!empty($flight['flights'][0]['ticket_also_sold_by']))
            <div class="card shadow p-4 mt-3">
                <h4>También vendido por</h4>
                <ul>
                    @foreach ($flight['flights'][0]['ticket_also_sold_by'] as $seller)
                        <li>{{ $seller }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="#" class="btn btn-primary">Reservar ahora</a>
        </div>
    </div>
</body>

</html>