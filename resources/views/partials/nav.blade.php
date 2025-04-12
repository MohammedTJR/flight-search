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
                <a href="{{ route('home') }}" class="nav-link {{ setActive('home') }}">
                    <i class="fas fa-home me-2"></i>Inicio
                </a>
            </li>
        </ul>
    </div>
</header>