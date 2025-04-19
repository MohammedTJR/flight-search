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
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    @yield('styles')
    @yield('script')
    <title>@yield('titulo_pagina')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

<body class="bg-light d-flex flex-column min-vh-100"> <!-- Añade estas clases -->
    <div class="flex-grow-1"> <!-- Este div crecerá para empujar el footer hacia abajo -->
        @include('partials.nav')
        @yield('contenido')
    </div>
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row gy-4">
                <!-- Logo y descripción -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <img src="{{ asset('img/logo.png') }}" alt="FlyLow Logo" class="img-fluid"
                            style="max-width: 150px;">
                    </div>
                    <p class="text-light">Encuentra los vuelos más económicos y planifica tu próximo viaje con FlyLow.
                    </p>
                    <div class="social-links d-flex gap-3 mt-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Links rápidos -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}"
                                class="text-decoration-none text-light">Inicio</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Buscar Vuelos</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Ofertas</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Destinos</a></li>
                    </ul>
                </div>

                <!-- Ayuda y soporte -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Ayuda</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Centro de ayuda</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Preguntas frecuentes</a>
                        </li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Política de privacidad</a>
                        </li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Términos y condiciones</a>
                        </li>
                    </ul>
                </div>

                <!-- Contacto -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i>info@flylow.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2 text-primary"></i>+34 900 123 456</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Madrid, España</li>
                    </ul>
                </div>
            </div>

            <!-- Línea divisoria -->
            <hr class="my-3 bg-secondary">

            <!-- Copyright -->
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} FlyLow. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Diseñado con <i class="fas fa-heart text-danger"></i> por TuNombre</p>
                </div>
            </div>
        </div>
    </footer>
    @yield('scripts')
</body>

</html>