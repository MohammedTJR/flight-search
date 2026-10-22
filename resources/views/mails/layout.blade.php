<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('title') - FlyLow</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }

        table {
            border-collapse: collapse;
        }

        a {
            color: #2563eb;
        }

        .card {
            width: 100%;
            max-width: 620px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        .hero {
            padding: 36px 32px 30px;
            text-align: center;
            color: #ffffff;
            background-color: #1d4ed8;
            background-image: linear-gradient(135deg, #2563eb 0%, #1d4ed8 55%, #1e3a8a 100%);
        }

        .badge {
            display: inline-block;
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 9999px;
        }

        .logo {
            display: block;
            width: 72px;
            height: 72px;
            margin: 20px auto 16px;
            background-color: #0f172a;
            border: 4px solid rgba(255, 255, 255, 0.9);
            border-radius: 50%;
        }

        .hero h1 {
            margin: 0 0 6px;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: #ffffff;
        }

        .hero p {
            margin: 0;
            font-size: 15px;
            line-height: 1.5;
            color: #dbeafe;
        }

        .body {
            padding: 32px;
        }

        .body h2 {
            margin: 0 0 10px;
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
        }

        .body p {
            margin: 0 0 16px;
            font-size: 15px;
            line-height: 1.65;
            color: #475569;
        }

        .brand {
            padding: 1px 6px;
            font-weight: 700;
            color: #78350f;
            background-color: #fef3c7;
            border-radius: 4px;
        }

        .panel {
            margin: 22px 0;
            padding: 18px 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .panel-title {
            margin: 0 0 12px;
            padding-bottom: 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .row td {
            padding: 6px 0;
            font-size: 14px;
            vertical-align: top;
        }

        .row .k {
            width: 42%;
            color: #64748b;
        }

        .row .v {
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }

        .pill {
            display: inline-block;
            padding: 2px 9px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 9999px;
        }

        .pill--amber {
            color: #b45309;
            background-color: #fffbeb;
            border: 1px solid #fde68a;
        }

        .pill--green {
            color: #047857;
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .note {
            margin: 22px 0;
            padding: 13px 16px;
            font-size: 14px;
            line-height: 1.55;
            border-radius: 10px;
        }

        .note--red {
            color: #b91c1c;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .note--green {
            color: #065f46;
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .note--amber {
            color: #78350f;
            background-color: #fffbeb;
            border: 1px solid #fde68a;
        }

        .note--grey {
            color: #64748b;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-block;
            min-width: 220px;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            color: #ffffff !important;
            background-color: #2563eb;
            border-radius: 12px;
        }

        .small {
            font-size: 12px !important;
            color: #94a3b8 !important;
        }

        .link-box {
            display: block;
            margin-top: 6px;
            padding: 9px 11px;
            font-family: Consolas, Menlo, monospace;
            font-size: 11px;
            line-height: 1.5;
            color: #1d4ed8;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            word-break: break-all;
        }

        .footer {
            padding: 28px 32px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            margin: 4px 0;
            line-height: 1.6;
        }

        .social a {
            display: inline-block;
            margin: 0 5px;
            padding: 6px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            line-height: 0;
        }

        .legal {
            margin-top: 16px;
            padding-top: 16px;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 480px) {

            .hero,
            .body,
            .footer {
                padding-left: 20px;
                padding-right: 20px;
            }

            .hero h1 {
                font-size: 23px;
            }

            .btn {
                display: block;
                min-width: 0;
            }

            .row .k {
                display: block;
                width: auto;
                padding-bottom: 0;
            }

            .row .v {
                display: block;
                padding-top: 0;
            }
        }
    </style>
</head>

<body>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#f1f5f9">
        <tr>
            <td align="center" style="padding: 28px 12px;">
                <table role="presentation" class="card" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="hero" bgcolor="#1d4ed8">
                            <span class="badge">@yield('badge')</span>
                            <img src="{{ asset('img/favicon.png') }}" alt="FlyLow" width="72" height="72" class="logo">
                            <h1>@yield('heading')</h1>
                            <p>@yield('subtitle')</p>
                        </td>
                    </tr>

                    <tr>
                        <td class="body">
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td class="footer">
                            <div class="social">
                                <a href="https://facebook.com/flylow"><img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook" width="18" height="18"></a>
                                <a href="https://twitter.com/flylow"><img src="https://cdn-icons-png.flaticon.com/512/5968/5968830.png" alt="X" width="18" height="18"></a>
                                <a href="https://instagram.com/flylow"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" width="18" height="18"></a>
                            </div>
                            <p style="margin-top: 14px; font-weight: 600; color: #475569;">
                                <span class="brand">FlyLow</span> — Encuentra los mejores vuelos al mejor precio
                            </p>
                            <p>
                                <a href="tel:+34603842135">+34 603 84 21 35</a> &nbsp;|&nbsp;
                                <a href="mailto:info@flylow.es">info@flylow.es</a>
                            </p>
                            <div class="legal">
                                @yield('legal')
                                <p>Este es un correo automático, por favor no respondas directamente a este mensaje.</p>
                                <p>© {{ date('Y') }} FlyLow. Todos los derechos reservados.</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
