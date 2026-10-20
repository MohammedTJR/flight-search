<footer class="fl-footer">
    <div class="fl-footer__inner">
        <div class="fl-footer__grid">
            <div>
                <a href="{{ route('home') }}" class="fl-brand">
                    <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo">
                    <span>FlyLow</span>
                </a>
                <p class="fl-footer__about">Encuentra los vuelos más económicos y planifica tu próximo viaje con FlyLow.
                </p>
            </div>

            <div>
                <h4 class="fl-footer__title">Exploración</h4>
                <ul class="fl-footer__list">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('radar') }}">Radar</a></li>
                    <li><a href="{{ route('favorites.show') }}">Favoritos</a></li>
                    <li><a href="{{ route('profile.show') }}">Mi cuenta</a></li>
                </ul>
            </div>

            <div>
                <h4 class="fl-footer__title">Asistencia y legal</h4>
                <ul class="fl-footer__list">
                    <li><a href="{{ route('help') }}">Centro de ayuda</a></li>
                    <li><a href="{{ route('faq') }}">Preguntas frecuentes</a></li>
                    <li><a href="{{ route('privacy') }}">Política de privacidad</a></li>
                    <li><a href="{{ route('terms') }}">Términos y condiciones</a></li>
                </ul>
            </div>

            <div>
                <h4 class="fl-footer__title">Contacto</h4>
                <ul class="fl-footer__list">
                    <li><a href="mailto:info@flylow.es"><i class="fas fa-envelope"></i>info@flylow.es</a></li>
                    <li><a href="tel:+34900123456"><i class="fas fa-phone"></i>+34 900 123 456</a></li>
                    <li><a href="https://maps.google.com/?q=Gandia,Valencia" target="_blank" rel="noopener"><i
                                class="fas fa-location-dot"></i>Gandía, Valencia</a></li>
                </ul>
            </div>
        </div>

        <div class="fl-footer__bottom">
            <span>&copy; {{ date('Y') }} FlyLow. Todos los derechos reservados.</span>
            <span>Diseñado por MOTJR</span>
        </div>
    </div>
</footer>
