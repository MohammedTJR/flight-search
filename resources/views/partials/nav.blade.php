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
                    @php
                        $navUser = Auth::user();
                        $navAvatar = $navUser->avatar ?? asset('img/default-avatar.png');
                        $navSeguidos = $navUser->trackedFlights()->count();
                        $navFavoritos = $navUser->favoriteFlights()->count();
                        $navBusquedas = $navUser->searchHistory()->count();
                    @endphp
                    <div class="dropdown">
                        <button class="fl-user dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Menú de usuario">
                            <span class="fl-user__text">
                                <strong>{{ $navUser->name }}</strong>
                                <small>Mi cuenta</small>
                            </span>
                            <img src="{{ $navAvatar }}" alt="" class="fl-user__avatar">
                            <i class="fas fa-chevron-down fl-user__caret"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end fl-menu">
                            <div class="fl-menu__head">
                                <img src="{{ $navAvatar }}" alt="" class="fl-menu__avatar">
                                <div>
                                    <strong>{{ $navUser->name }}
                                        @if($navUser->email_verified_at)
                                            <i class="fas fa-circle-check" title="Cuenta verificada"></i>
                                        @endif
                                    </strong>
                                    <span>{{ $navUser->email }}</span>
                                </div>
                            </div>

                            <div class="fl-menu__stats">
                                <a href="{{ route('flight.tracking.index') }}"><b>{{ $navSeguidos }}</b><span>Vuelos</span></a>
                                <a href="{{ route('favorites.show') }}"><b>{{ $navFavoritos }}</b><span>Favoritos</span></a>
                                <a href="{{ route('profile.show') }}#search-history"><b>{{ $navBusquedas }}</b><span>Búsquedas</span></a>
                            </div>

                            <nav class="fl-menu__list">
                                <a href="{{ route('flight.tracking.index') }}"
                                    class="{{ request()->routeIs('flight.tracking.*') ? 'is-active' : '' }}">
                                    <i class="fas fa-plane-departure"></i><span>Mis vuelos</span>
                                    @if($navSeguidos > 0)<em>{{ $navSeguidos }} {{ $navSeguidos === 1 ? 'activo' : 'activos' }}</em>@endif
                                </a>
                                <a href="{{ route('favorites.show') }}"
                                    class="{{ request()->routeIs('favorites.*') ? 'is-active' : '' }}">
                                    <i class="far fa-heart"></i><span>Favoritos</span>
                                    @if($navFavoritos > 0)<em>{{ $navFavoritos }}</em>@endif
                                </a>
                                <a href="{{ route('profile.show') }}#search-history">
                                    <i class="fas fa-clock-rotate-left"></i><span>Historial de búsquedas</span>
                                </a>
                                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'is-active' : '' }}">
                                    <i class="far fa-circle-user"></i><span>Mi perfil</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                                    <i class="fas fa-sliders"></i><span>Ajustes y privacidad</span>
                                </a>
                            </nav>

                            <form method="POST" action="{{ route('logout') }}" class="fl-menu__logout">
                                @csrf
                                <button type="submit" class="logout-button">
                                    <i class="fas fa-right-from-bracket"></i>Cerrar sesión
                                </button>
                            </form>
                        </div>
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
