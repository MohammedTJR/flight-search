<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('styles')
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