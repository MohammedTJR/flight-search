<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Precio en tu Vuelo Favorito</title>
    <style>
        /* Estilos base */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #3b7ddd;
            padding: 30px 20px;
            text-align: center;
        }

        .logo {
            max-width: 180px;
            height: auto;
        }

        .header h1 {
            color: #ffffff;
            margin: 20px 0 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .flight-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }

        .price-change {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }

        .info-label {
            font-weight: 600;
            color: #3b7ddd;
        }

        .price-old {
            color: #dc3545;
            text-decoration: line-through;
        }

        .price-new {
            color: #28a745;
            font-weight: bold;
            font-size: 1.2em;
        }

        .cta-button {
            display: inline-block;
            background-color: #3b7ddd;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 30px;
            margin: 20px 0;
            text-align: center;
            transition: all 0.3s ease;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 14px;
        }

        .disclaimer {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Cabecera con logo -->
        <div class="header">
            <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="logo">
            <h1>¡El precio de tu vuelo ha cambiado!</h1>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <div class="flight-info">
                <h2>Detalles del vuelo:</h2>
                <p><span class="info-label">Origen:</span> {{ $flight->origin }}</p>
                <p><span class="info-label">Destino:</span> {{ $flight->destination }}</p>
                <p><span class="info-label">Fecha:</span> {{ $flight->departure_date->format('d/m/Y') }}</p>
                <p><span class="info-label">Aerolínea:</span> {{ $flight->airline }}</p>
            </div>

            <div class="price-change">
                <h3>¡Atención al cambio de precio!</h3>
                <p><span class="price-old">{{ number_format($oldPrice, 2) }}€</span></p>
                <p><span class="price-new">{{ number_format($newPrice, 2) }}€</span></p>
                <p>El precio ha {{ $changeType }} {{ number_format($changeAmount, 2) }}€</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('favorites.details', $flight->id) }}" class="cta-button">
                    Ver detalles del vuelo
                </a>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>FlyLow - Encuentra los mejores vuelos al mejor precio</p>
            <p>+34 603 84 21 35 | info@flylow.es</p>

            <div class="disclaimer">
                <p>Este es un correo automático, por favor no respondas directamente a este mensaje.</p>
                <p>Recibes este correo porque tienes activadas las alertas de precios para tus vuelos favoritos.</p>
                <p>© {{ date('Y') }} FlyLow. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>