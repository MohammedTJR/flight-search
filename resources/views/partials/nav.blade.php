<header class="d-flex flex-wrap justify-content-center py-3 border-bottom shadow-sm sticky-top bg-white">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <!-- Logo y nombre -->
        <a href="/" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-primary text-decoration-none">
            <img src="{{ asset('img/favicon.png') }}" alt="FlyLow Logo" style="width:50px; height:auto;" class="me-2">
            <span class="fs-3 fw-semibold">FlyLow</span>
        </a>

        <!-- Menú de navegación -->
        <ul class="nav nav-pills align-items-center">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i>Inicio
                </a>
            </li>

            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Mi Perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('favorites.show') }}">Favoritos</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</header>