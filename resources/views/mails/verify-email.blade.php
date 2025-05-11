<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu email - FlyLow</title>
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

        /* Contenedor principal */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Cabecera */
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

        /* Contenido */
        .content {
            padding: 30px;
        }

        .welcome-text {
            font-size: 18px;
            margin-bottom: 25px;
        }

        .user-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: 600;
            color: #3b7ddd;
        }

        /* Botón */
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

        .cta-button:hover {
            background-color: #2f6bc5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(59, 125, 221, 0.3);
        }

        /* Pie de página */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 14px;
        }

        .footer p {
            margin: 5px 0;
        }

        .social-links {
            margin: 15px 0;
        }

        .social-links a {
            margin: 0 10px;
            display: inline-block;
        }

        .disclaimer {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .verification-note {
            color: #388e3c;
            font-weight: 600;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Cabecera con logo -->
        <div class="header">
            <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" class="logo">
            <h1>Verifica tu dirección de email</h1>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <p class="welcome-text">¡Ya casi estás listo, {{ $user->name }}!</p>
            <p>Para completar tu registro en FlyLow y comenzar a disfrutar de nuestros servicios, por favor verifica tu dirección de email.</p>

            <div class="user-info">
                <div class="info-item">
                    <span class="info-label">Email a verificar:</span> {{ $user->email }}
                </div>
                <div class="info-item">
                    <span class="info-label">ID de usuario:</span> FLY-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <p class="verification-note">Por tu seguridad, verifica que esta dirección de email sea correcta.</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="cta-button">Verificar Mi Email</a>
            </div>

            <p>Si no creaste una cuenta en FlyLow, por favor ignora este mensaje o contacta con nuestro equipo de soporte.</p>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <div class="social-links">
                <a href="https://facebook.com/flylow">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook" width="24">
                </a>
                <a href="https://twitter.com/flylow">
                <img src="https://abs.twimg.com/favicons/twitter.3.ico" alt="X" width="24">
                </a>
                <a href="https://instagram.com/flylow">
                    <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" width="24">
                </a>
            </div>

            <p>FlyLow - Encuentra los mejores vuelos al mejor precio</p>
            <p>+34 603 84 21 35 | info@flylow.es</p>

            <div class="disclaimer">
                <p>Este es un correo automático, por favor no respondas directamente a este mensaje.</p>
                <p>© {{ date('Y') }} FlyLow. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>

</html>