@php
    $travelClasses = [
        '1' => 'Económica',
        '2' => 'Económica Premium',
        '3' => 'Business',
        '4' => 'Primera clase'
    ];
@endphp

@foreach($searchHistory as $search)
    <div class="search-history-item mb-3 p-3 border rounded shadow-sm bg-white hover-effect">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-2">
                    <div class="route-icon me-3">
                        <i class="fas fa-plane-departure text-primary"></i>
                        <div class="route-line"></div>
                        <i class="fas fa-plane-arrival text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">
                            {{ $search->origin }} <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            {{ $search->destination }}
                        </h6>
                        <div class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $search->departure_date->format('d M Y') }}
                            @if($search->return_date)
                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                {{ $search->return_date->format('d M Y') }}
                            @endif
                            <span class="ms-3">
                                <i class="far fa-clock me-1"></i>
                                {{ $search->created_at->format('d/m H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-2">
                    <div class="badge bg-light text-dark border">
                        <i class="fas fa-users me-1"></i>
                        {{ $search->adults }} adulto(s)
                    </div>

                    @if($search->children > 0)
                        <div class="badge bg-light text-dark border">
                            <i class="fas fa-child me-1"></i>
                            {{ $search->children }} niño(s)
                        </div>
                    @endif

                    @if($search->infants_in_seat > 0)
                        <div class="badge bg-light text-dark border">
                            <i class="fas fa-baby me-1"></i>
                            {{ $search->infants_in_seat }} bebé(s) con asiento
                        </div>
                    @endif

                    @if($search->infants_on_lap > 0)
                        <div class="badge bg-light text-dark border">
                            <i class="fas fa-baby-carriage me-1"></i>
                            {{ $search->infants_on_lap }} bebé(s) en regazo
                        </div>
                    @endif

                    <div class="badge bg-light text-dark border">
                        <i class="fas fa-chair me-1"></i>
                        {{ $travelClasses[$search->travel_class] ?? 'No especificada' }}
                    </div>

                    <div class="badge bg-light text-dark border">
                        <i class="fas fa-exchange-alt me-1"></i>
                        {{ $search->stops ? 'Sin escalas' : 'Con escalas' }}
                    </div>
                </div>
            </div>

            <form action="{{ route('flights') }}" method="GET" class="ms-3">
                <input type="hidden" name="departure" value="{{ $search->origin }}">
                <input type="hidden" name="arrival" value="{{ $search->destination }}">
                <input type="hidden" name="date" value="{{ $search->departure_date->format('Y-m-d') }}">
                <input type="hidden" name="adults" value="{{ $search->adults }}">
                <input type="hidden" name="children" value="{{ $search->children }}">
                <input type="hidden" name="infants_in_seat" value="{{ $search->infants_in_seat }}">
                <input type="hidden" name="infants_on_lap" value="{{ $search->infants_on_lap }}">
                <input type="hidden" name="travel_class" value="{{ $search->travel_class }}">
                <input type="hidden" name="stops" value="{{ $search->stops ? 1 : 0 }}">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-search me-1"></i> Buscar
                </button>
            </form>
        </div>
    </div>
@endforeach