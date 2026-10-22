@extends('plantilla')

@section('titulo_pagina', 'Favoritos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ filemtime(public_path('css/content.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}?v={{ filemtime(public_path('css/favorites.css')) }}">
@endsection

@section('contenido')
    @php
        $total = count($favorites);
        $masBarato = $favorites->sortBy('price')->first();
        $proximo = $favorites->sortBy('departure_date')->first();
        $directos = $favorites->filter(function ($f) {
            $d = $f->flight_details ?? [];
            return count($d['layovers'] ?? []) === 0;
        })->count();
    @endphp

    <div class="fv-wrap">
        <div class="fv-crumbs">
            <ol class="ct-crumbs">
                <li><a href="{{ route('profile.show') }}">Panel de usuario</a></li>
                <li>Favoritos</li>
            </ol>
        </div>

        <header class="fv-head">
            <div class="fv-head__title">
                <span class="fv-head__icon"><i class="fas fa-heart"></i></span>
                <div>
                    <h1>Tus Vuelos Favoritos</h1>
                    <p>Vigilamos el precio de los vuelos que guardas y te avisamos por correo si cambia.</p>
                </div>
            </div>
            <span class="fv-count"><i class="fas fa-bookmark"></i>{{ $total }} {{ $total === 1 ? 'vuelo guardado' : 'vuelos guardados' }}</span>
        </header>

        @if(session('success'))
            <div class="fv-alert fv-alert--ok"><i class="fas fa-circle-check"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="fv-alert fv-alert--error"><i class="fas fa-triangle-exclamation"></i>{{ session('error') }}</div>
        @endif

        @if($total > 0)
            <section class="fv-summary ct-card">
                <div class="fv-summary__item">
                    <span class="fv-summary__ico"><i class="fas fa-tag"></i></span>
                    <div>
                        <small>Precio más bajo</small>
                        <strong>{{ number_format($masBarato->price, 2, ',', '.') }} €</strong>
                        <em>{{ $masBarato->origin }} → {{ $masBarato->destination }}</em>
                    </div>
                </div>
                <div class="fv-summary__item">
                    <span class="fv-summary__ico"><i class="far fa-calendar-check"></i></span>
                    <div>
                        <small>Próxima salida</small>
                        <strong>{{ $proximo->departure_date->locale('es')->translatedFormat('d M Y') }}</strong>
                        <em>{{ $proximo->origin }} → {{ $proximo->destination }}</em>
                    </div>
                </div>
                <div class="fv-summary__item">
                    <span class="fv-summary__ico"><i class="fas fa-plane-arrival"></i></span>
                    <div>
                        <small>Vuelos directos</small>
                        <strong>{{ $directos }} de {{ $total }}</strong>
                        <em>sin escalas</em>
                    </div>
                </div>
            </section>

            <div class="fv-toolbar">
                <div class="fv-tabs" role="tablist" aria-label="Filtrar favoritos">
                    <button type="button" class="is-active" data-filter="all" role="tab" aria-selected="true">
                        Todos <span>{{ $total }}</span>
                    </button>
                    <button type="button" data-filter="direct" role="tab" aria-selected="false">
                        Vuelos directos <span>{{ $directos }}</span>
                    </button>
                </div>
                <label class="fv-sort">
                    <span>Ordenar</span>
                    <select id="fvSort">
                        <option value="recent">Añadidos recientemente</option>
                        <option value="price">Precio más bajo</option>
                        <option value="date">Fecha de salida</option>
                    </select>
                </label>
            </div>

            <div class="fv-grid" id="fvGrid">
                @foreach ($favorites as $favorite)
                    @php
                        $d = $favorite->flight_details ?? [];
                        $segmentos = $d['flights'] ?? [];
                        $primero = $segmentos[0] ?? [];
                        $ultimo = $segmentos ? $segmentos[count($segmentos) - 1] : [];
                        $escalas = count($d['layovers'] ?? []);
                        $duracion = $d['total_duration'] ?? null;
                        $horaSalida = isset($primero['departure_airport']['time']) ? substr($primero['departure_airport']['time'], 11, 5) : null;
                        $horaLlegada = isset($ultimo['arrival_airport']['time']) ? substr($ultimo['arrival_airport']['time'], 11, 5) : null;
                        $nombreOrigen = $primero['departure_airport']['name'] ?? $favorite->origin;
                        $nombreDestino = $ultimo['arrival_airport']['name'] ?? $favorite->destination;
                        $pasajeros = $favorite->adults + $favorite->children + $favorite->infants_in_seat + $favorite->infants_on_lap;
                    @endphp
                    <article class="fv-card" data-direct="{{ $escalas === 0 ? 1 : 0 }}" data-price="{{ $favorite->price }}"
                        data-date="{{ $favorite->departure_date->timestamp }}" data-created="{{ $favorite->created_at->timestamp }}">
                        <div class="fv-card__media">
                            <img src="{{ $favorite->destination_image }}" alt="{{ $favorite->destination }}" loading="lazy">
                            <span class="fv-badge fv-badge--airline"><i class="fas fa-plane"></i>{{ $favorite->airline }}</span>
                            <span class="fv-badge fv-badge--pax" title="Pasajeros"><i class="fas fa-users"></i>{{ $pasajeros }}</span>
                        </div>

                        <div class="fv-card__body">
                            <div class="fv-route">
                                <div class="fv-route__codes">
                                    <span>{{ $favorite->origin }}</span>
                                    <i class="fas fa-arrow-right"></i>
                                    <span class="is-dest">{{ $favorite->destination }}</span>
                                </div>
                                @if($duracion)
                                    <span class="fv-route__meta">
                                        <i class="far fa-clock"></i>{{ intdiv($duracion, 60) }}h {{ str_pad($duracion % 60, 2, '0', STR_PAD_LEFT) }}m
                                        · {{ $escalas === 0 ? 'Directo' : $escalas . ($escalas === 1 ? ' escala' : ' escalas') }}
                                    </span>
                                @endif
                            </div>
                            <p class="fv-airports">{{ $nombreOrigen }} → {{ $nombreDestino }}</p>

                            <div class="fv-info">
                                <span class="fv-info__date"><i class="far fa-calendar-alt"></i>{{ $favorite->departure_date->locale('es')->translatedFormat('d M Y') }}</span>
                                <span class="fv-info__price">{{ number_format($favorite->price, 2) }}€</span>
                            </div>

                            @if($horaSalida && $horaLlegada)
                                <div class="fv-times">
                                    <div>
                                        <small>Salida</small>
                                        <strong>{{ $horaSalida }}</strong>
                                    </div>
                                    <span class="fv-times__line"><i class="fas fa-plane"></i></span>
                                    <div>
                                        <small>Llegada</small>
                                        <strong>{{ $horaLlegada }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="fv-card__actions">
                            <a href="{{ route('favorites.details', $favorite->id) }}" class="fv-btn fv-btn--primary">
                                <i class="fas fa-eye"></i>Ver detalles
                            </a>
                            <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar este vuelo de tus favoritos?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="fv-btn fv-btn--danger" title="Eliminar de favoritos">
                                    <i class="fas fa-trash-alt"></i><span>Eliminar</span>
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach

                <article class="fv-card fv-discover" id="fvDiscover">
                    <span class="fv-discover__icon"><i class="fas fa-bell"></i></span>
                    <h2>¿Buscando otro destino?</h2>
                    <p>Guarda cualquier vuelo desde el buscador con el icono <i class="fas fa-heart"></i> y lo verás aquí.</p>
                    <a href="{{ route('home') }}" class="fv-btn fv-btn--dark"><i class="fas fa-magnifying-glass"></i>Buscar vuelos</a>
                </article>
            </div>

            <p class="fv-empty-filter" id="fvEmptyFilter" hidden>Ningún favorito cumple este filtro.</p>

            <section class="fv-how ct-card">
                <h2><i class="far fa-circle-question"></i>¿Cómo funciona el seguimiento de precios?</h2>
                <div class="fv-how__grid">
                    <div>
                        <b><i class="fas fa-rotate"></i>1. Revisión periódica</b>
                        <p>Consultamos el precio de tus vuelos guardados cada cuatro horas.</p>
                    </div>
                    <div>
                        <b><i class="far fa-envelope"></i>2. Aviso por correo</b>
                        <p>Si el precio cambia, te enviamos un correo con la tarifa anterior y la nueva.</p>
                    </div>
                    <div>
                        <b><i class="far fa-calendar-xmark"></i>3. Limpieza automática</b>
                        <p>Los favoritos se eliminan solos cuando la fecha de salida ya ha pasado.</p>
                    </div>
                </div>
            </section>
        @else
            <div class="fv-empty ct-card">
                <span class="fv-empty__icon"><i class="far fa-heart"></i></span>
                <h2>No tienes vuelos favoritos</h2>
                <p>Busca vuelos y guárdalos con el icono <i class="fas fa-heart"></i> para verlos aquí y seguir su precio.</p>
                <a href="{{ route('home') }}" class="ct-btn ct-btn--primary"><i class="fas fa-magnifying-glass"></i>Buscar vuelos</a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/favorites.js') }}?v={{ filemtime(public_path('js/favorites.js')) }}"></script>
@endsection
