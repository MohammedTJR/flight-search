@php
    $travelClasses = [
        '1' => 'Económica',
        '2' => 'Económica Premium',
        '3' => 'Business',
        '4' => 'Primera clase'
    ];
    // Fechas en español sin los puntos de las abreviaturas ("20 oct 2026")
    $fmtFecha = fn($d) => str_replace('.', '', $d->copy()->locale('es')->translatedFormat('d M Y'));
@endphp

@foreach($searchHistory as $search)
    <article class="pf-search">
        <span class="pf-search-ico"><i class="fas fa-plane-departure"></i></span>

        <div class="pf-search-main">
            <h3>{{ $search->origin }} <i class="fas fa-arrow-right"></i> {{ $search->destination }}</h3>
            <p>
                <span><i class="far fa-calendar"></i>{{ $fmtFecha($search->departure_date) }}
                    @if($search->return_date)
                        <i class="fas fa-arrow-right pf-arrow"></i>{{ $fmtFecha($search->return_date) }}
                    @endif
                </span>
                <span class="pf-muted"><i class="far fa-clock"></i>Buscado: {{ $search->created_at->format('d/m H:i') }}</span>
            </p>
        </div>

        <div class="pf-search-tags">
            <span><i class="far fa-user"></i>{{ $search->adults }} adulto(s)</span>
            @if($search->children > 0)
                <span><i class="fas fa-child"></i>{{ $search->children }} niño(s)</span>
            @endif
            @if($search->infants_in_seat > 0)
                <span><i class="fas fa-baby"></i>{{ $search->infants_in_seat }} bebé(s) con asiento</span>
            @endif
            @if($search->infants_on_lap > 0)
                <span><i class="fas fa-baby-carriage"></i>{{ $search->infants_on_lap }} bebé(s) en regazo</span>
            @endif
            <span><i class="fas fa-chair"></i>{{ $travelClasses[$search->travel_class] ?? 'No especificada' }}</span>
            <span><i class="fas fa-right-left"></i>{{ $search->stops ? 'Sin escalas' : 'Con escalas' }}</span>
        </div>

        <form action="{{ route('flights') }}" method="GET" class="pf-search-form">
            <input type="hidden" name="departure" value="{{ $search->origin }}">
            <input type="hidden" name="arrival" value="{{ $search->destination }}">
            <input type="hidden" name="date" value="{{ $search->departure_date->format('Y-m-d') }}">
            <input type="hidden" name="adults" value="{{ $search->adults }}">
            <input type="hidden" name="children" value="{{ $search->children }}">
            <input type="hidden" name="infants_in_seat" value="{{ $search->infants_in_seat }}">
            <input type="hidden" name="infants_on_lap" value="{{ $search->infants_on_lap }}">
            <input type="hidden" name="travel_class" value="{{ $search->travel_class }}">
            <input type="hidden" name="stops" value="{{ $search->stops ? 1 : 0 }}">
            <button type="submit" class="pf-btn pf-btn--primary pf-btn--sm">
                <i class="fas fa-magnifying-glass"></i>Buscar
            </button>
        </form>
    </article>
@endforeach
