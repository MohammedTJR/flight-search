<header class="d-flex flex-wrap justify-content-center py-3 border-bottom shadow-sm sticky-top bg-white">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <!-- Logo y nombre -->
        <a href="{{ route('home') }}"
            class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-primary text-decoration-none">
            <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" style="width:50px; height:auto;" class="me-2">
            <span class="fs-3 fw-semibold">FlyLow</span>
        </a>

        <!-- Menú de navegación -->
        <ul class="nav nav-pills align-items-center">
            <!-- Enlace al Radar (siempre visible) -->
            <li class="nav-item">
                <a href="{{ route('radar') }}" class="nav-link {{ request()->routeIs('radar.*') ? 'active' : '' }}">
                    <i class="fas fa-satellite-dish me-2"></i>Radar en vivo
                </a>
            </li>

            @auth
                <!-- Dropdown para usuarios autenticados (visible en todas las pantallas) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('flight.tracking.index') }}"><i
                                    class="fas fa-plane me-2"></i>Mis Vuelos</a></li>
                        <li><a class="dropdown-item" href="{{ route('favorites.show') }}"><i
                                    class="fas fa-heart me-2"></i>Favoritos</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i
                                    class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item logout-button">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <!-- Enlaces para usuarios no autenticados -->
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</header>