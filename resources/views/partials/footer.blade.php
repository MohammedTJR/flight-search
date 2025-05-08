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
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none text-light">Inicio</a>
                    </li>
                    <li class="mb-2"><a href="{{ route('radar') }}" class="text-decoration-none text-light">Radar</a>
                    </li>
                    <li class="mb-2"><a href="{{ route('favorites.show') }}"
                            class="text-decoration-none text-light">Favoritos</a></li>
                    <li class="mb-2"><a href="{{ route('profile.show') }}" class="text-decoration-none text-light">Mi
                            cuenta</a></li>
                </ul>
            </div>

            <!-- Ayuda y soporte -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">Ayuda</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('help') }}" class="text-decoration-none text-light">Centro de
                            ayuda</a></li>
                    <li class="mb-2"><a href="{{ route('faq') }}" class="text-decoration-none text-light">Preguntas
                            frecuentes</a>
                    </li>
                    <li class="mb-2"><a href="{{ route('privacy') }}" class="text-decoration-none text-light">Política
                            de privacidad</a>
                    </li>
                    <li class="mb-2"><a href="{{ route('terms') }}" class="text-decoration-none text-light">Términos y
                            condiciones</a>
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
                <p class="mb-0">Diseñado con <i class="fas fa-heart text-danger"></i> por MOTJR & Ruth V.M</p>
            </div>
        </div>
    </div>
</footer>