<header class="fl-nav">
    <div class="fl-nav__inner">
        <a href="{{ route('home') }}" class="fl-brand">
            <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo">
            <span>FlyLow</span>
        </a>

        <button class="fl-nav__toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#flNavMenu"
            aria-controls="flNavMenu" aria-expanded="false" aria-label="Abrir menú">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse fl-nav__menu" id="flNavMenu">
            <nav class="fl-nav__links">
                <a href="{{ route('radar') }}" class="fl-nav__link {{ request()->routeIs('radar*') ? 'active' : '' }}">
                    <i class="fas fa-satellite-dish"></i>Radar en vivo
                </a>
                @auth
                    <a href="{{ route('flight.tracking.index') }}"
                        class="fl-nav__link {{ request()->routeIs('flight.tracking.*') ? 'active' : '' }}">Mis vuelos</a>
                    <a href="{{ route('favorites.show') }}"
                        class="fl-nav__link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">Favoritos</a>
                @endauth
                <a href="{{ route('help') }}" class="fl-nav__link {{ request()->routeIs('help') ? 'active' : '' }}">Centro
                    de ayuda</a>
            </nav>

            <div class="fl-nav__actions">
                @auth
                    <div class="dropdown">
                        <button class="fl-nav__link fl-account dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="fl-avatar"><i class="fas fa-user"></i></span>
                            {{ Auth::user()->name }}
                            <i class="fas fa-chevron-down small"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end fl-dropdown">
                            <li><a class="dropdown-item" href="{{ route('flight.tracking.index') }}"><i
                                        class="fas fa-plane"></i>Mis vuelos</a></li>
                            <li><a class="dropdown-item" href="{{ route('favorites.show') }}"><i
                                        class="fas fa-heart"></i>Favoritos</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i
                                        class="fas fa-user"></i>Mi perfil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-button">
                                        <i class="fas fa-sign-out-alt"></i>Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="fl-nav__link fl-nav__link--cta {{ request()->routeIs('login') ? 'active' : '' }}">
                        <i class="fas fa-right-to-bracket"></i>Iniciar sesión
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
