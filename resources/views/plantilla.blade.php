<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @yield('styles')
    @yield('styles_ext')
    @yield('script')
    <title>@yield('titulo_pagina')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

<body class="bg-light">

    <div>
        @include('partials.nav')
        @yield('contenido')
    </div>
    <footer>
        <div class="footer-content">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="FlyLow Logo">
            </div>
            <div class="derechos">
                <p>&copy; {{ date('Y') }} FlyLow. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>

</html>
