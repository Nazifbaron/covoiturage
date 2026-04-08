@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- BANNIÈRE --}}
    <div class="relative overflow-hidden rounded-2xl p-6
                bg-gradient-to-br from-[#1a0a3e] via-[#2d1270] to-[#1a0a3e]
                flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-violet-500/10 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 w-40 h-40 rounded-full bg-orange-500/10 translate-y-1/2 pointer-events-none"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-violet-300/70 uppercase tracking-widest mb-1">
                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </p>
            <h2 class="text-2xl font-black text-white">Bonjour, {{ Auth::user()->first_name }} 👋</h2>
            <p class="text-violet-200/60 text-sm mt-1">
                @if(Auth::user()->role === 'driver') Gérez vos trajets et suivez vos gains en temps réel.
                @else Trouvez votre prochain trajet ou consultez vos réservations. @endif
            </p>
        </div>
        @if(Auth::user()->role === 'driver')
            <a href="{{ route('driver.trips.create') }}"
               class="relative z-10 flex-shrink-0 inline-flex items-center gap-2
                      bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] hover:opacity-90
                      text-white font-black px-5 py-3 rounded-xl transition-all shadow-lg shadow-violet-900/50 text-sm">
                <span class="material-symbols-outlined text-xl">add_circle</span>Publier un trajet
            </a>
        @else
            <div class="relative z-10 flex flex-col sm:flex-row gap-2 flex-shrink-0">
                <a href="{{ route('passenger.trips') }}"
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold px-5 py-3 rounded-xl text-sm">
                    <span class="material-symbols-outlined text-xl">search</span>Rechercher
                </a>
                <a href="{{ route('passenger.showtrips') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-[#E8470A] to-[#F97316] hover:opacity-90
                          text-white font-black px-5 py-3 rounded-xl shadow-lg shadow-orange-900/40 text-sm">
                    <span class="material-symbols-outlined text-xl">hail</span>Demander un trajet
                </a>
            </div>
        @endif
    </div>

    {{-- STATS --}}
    @php
        $stats = Auth::user()->role === 'driver' ? [
            ['icon'=>'payments',        'value'=> number_format($thisMonthEarnings, 0, ',', ' '), 'unit'=>'FCFA', 'label'=>'Gains ce mois',       'from'=>'from-[#6C2BD9]', 'to'=>'to-[#8B5CF6]', 'badge'=>''],
            ['icon'=>'directions_car',  'value'=> $tripsCount,                                   'unit'=>'',     'label'=>'Trajets publiés',     'from'=>'from-blue-500',  'to'=>'to-indigo-600', 'badge'=>''],
            ['icon'=>'pending_actions', 'value'=> $pendingRequestsCount,                         'unit'=>'',     'label'=>'Demandes en attente', 'from'=>'from-[#E8470A]', 'to'=>'to-[#F97316]', 'badge'=> $pendingRequestsCount > 0 ? $pendingRequestsCount : ''],
            ['icon'=>'route',           'value'=> $recentTrips->count(),                         'unit'=>'',     'label'=>'Trajets récents',     'from'=>'from-[#4C1D95]', 'to'=>'to-[#6C2BD9]', 'badge'=>''],
        ] : [
            ['icon'=>'bookmark',    'value'=> $activeCount,    'unit'=>'', 'label'=>'Réservations actives', 'from'=>'from-[#6C2BD9]', 'to'=>'to-[#8B5CF6]', 'badge'=>''],
            ['icon'=>'history',     'value'=> $completedCount, 'unit'=>'', 'label'=>'Trajets effectués',    'from'=>'from-blue-500',  'to'=>'to-indigo-600', 'badge'=>''],
            ['icon'=>'hail',        'value'=> $pendingCount,   'unit'=>'', 'label'=>'Demandes en cours',    'from'=>'from-[#E8470A]', 'to'=>'to-[#F97316]', 'badge'=> $pendingCount > 0 ? $pendingCount : ''],
            ['icon'=>'check_circle','value'=> $completedCount, 'unit'=>'', 'label'=>'Courses acceptées',    'from'=>'from-[#4C1D95]', 'to'=>'to-[#6C2BD9]', 'badge'=>''],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($stats as $s)
            <div class="relative overflow-hidden rounded-2xl p-5 bg-gradient-to-br {{ $s['from'] }} {{ $s['to'] }} shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">{{ $s['icon'] }}</span>
                    </div>
                    @if($s['badge']) <span class="text-xs font-bold text-white bg-white/20 px-2 py-0.5 rounded-full">{{ $s['badge'] }}</span> @endif
                </div>
                <p class="text-xl font-black text-white">{{ $s['value'] }} @if($s['unit'])<span class="text-sm font-semibold text-white/70">{{ $s['unit'] }}</span>@endif</p>
                <p class="text-xs text-white/70 mt-1 font-medium">{{ $s['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- CONTENU PRINCIPAL --}}
    @if(Auth::user()->role === 'driver')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Trajets publiés --}}
        <div class="lg:col-span-2 rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-violet-100 dark:border-violet-900/20 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-900/20 dark:to-transparent border-b border-violet-100 dark:border-violet-900/20">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#6C2BD9] text-base">directions_car</span>
                    </div>
                    <h3 class="font-black text-base">Mes trajets publiés</h3>
                </div>
                <a href="{{ route('driver.my-trips') }}" class="text-[#6C2BD9] text-sm font-bold hover:underline">Voir tout</a>
            </div>
            <div class="divide-y divide-violet-50 dark:divide-white/5">
                @forelse($recentTrips as $trip)
                @php
                    $colors = ['bg-violet-50 dark:bg-violet-500/10 text-[#6C2BD9]', 'bg-blue-50 dark:bg-blue-500/10 text-blue-500', 'bg-red-50 dark:bg-red-500/10 text-red-400'];
                    $ci = $loop->index % 3;
                    $isFull = $trip->seats_available <= 0;
                @endphp
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-violet-50/50 dark:hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 rounded-xl {{ $colors[$ci] }} bg-opacity-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">directions_car</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm">{{ $trip->departure_city }} → {{ $trip->arrival_city }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($trip->departure_date)->translatedFormat('d M Y') }}
                            à {{ substr($trip->departure_time, 0, 5) }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-black text-sm text-[#6C2BD9]">{{ number_format($trip->price_per_seat, 0, ',', ' ') }} FCFA</p>
                        <span class="text-xs font-semibold {{ $isFull ? 'text-red-400' : 'text-[#6C2BD9]' }}">
                            {{ $isFull ? 'Complet' : $trip->seats_available.' place'.($trip->seats_available > 1 ? 's' : '') }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-slate-400">Aucun trajet publié pour l'instant.</p>
                    <a href="{{ route('driver.trips.create') }}" class="inline-flex items-center gap-1 mt-3 text-xs font-bold text-[#6C2BD9] hover:underline">
                        <span class="material-symbols-outlined text-sm">add_circle</span>Publier un trajet
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="flex flex-col gap-4">
            {{-- Demandes passagers --}}
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-orange-100 dark:border-orange-900/20 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-transparent border-b border-orange-100 dark:border-orange-900/20">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#E8470A] text-base">pending_actions</span>
                        </div>
                        <h3 class="font-black text-base">Demandes</h3>
                    </div>
                    @if($pendingRequestsCount > 0)
                    <span class="bg-gradient-to-r from-[#E8470A] to-[#F97316] text-white text-xs font-black px-2.5 py-1 rounded-full">{{ $pendingRequestsCount }}</span>
                    @endif
                </div>
                <div class="divide-y divide-orange-50 dark:divide-white/5">
                    @forelse($pendingPastrips as $pastrip)
                    <div class="px-5 py-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C2BD9] to-[#4C1D95] flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-white text-base">person</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-sm">{{ $pastrip->user->first_name }} {{ $pastrip->user->last_name }}</p>
                                <p class="text-xs text-slate-500">{{ $pastrip->departure_city }} → {{ $pastrip->arrival_city }}</p>
                            </div>
                            <span class="text-xs text-slate-400 flex-shrink-0">{{ $pastrip->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('driver.requests') }}"
                               class="flex-1 py-1.5 rounded-lg bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] text-white text-xs font-bold hover:opacity-90 transition-opacity text-center">
                                Voir
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-xs text-slate-400">Aucune demande en attente</p>
                    </div>
                    @endforelse
                </div>
                @if($pendingRequestsCount > 0)
                <div class="px-5 py-3 border-t border-orange-50 dark:border-white/5">
                    <a href="{{ route('driver.requests') }}" class="text-xs font-bold text-[#6C2BD9] hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">open_in_new</span>Voir toutes les demandes
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    @else

    {{-- PASSAGER --}}

    {{-- Recherche --}}
    <div class="rounded-2xl p-5 bg-gradient-to-br from-[#1a0a3e] via-[#2d1270] to-[#1a0a3e] shadow-lg shadow-violet-900/30">
        <h3 class="font-black text-base text-white mb-4">Rechercher un trajet</h3>
        <form action="{{ route('search.results') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-violet-300/70 uppercase tracking-wide">Départ</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                        <span class="material-symbols-outlined text-[#8B5CF6] text-lg">trip_origin</span>
                        <input type="text" name="departure" placeholder="Ex: Cotonou"
                               class="flex-1 bg-transparent text-sm font-medium outline-none placeholder-slate-500 text-white"/>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-violet-300/70 uppercase tracking-wide">Destination</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                        <span class="material-symbols-outlined text-[#F97316] text-lg">location_on</span>
                        <input type="text" name="arrival" placeholder="Ex: Porto-Novo"
                               class="flex-1 bg-transparent text-sm font-medium outline-none placeholder-slate-500 text-white"/>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-violet-300/70 uppercase tracking-wide">Date</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                        <span class="material-symbols-outlined text-slate-400 text-lg">calendar_today</span>
                        <input type="date" name="date" class="flex-1 bg-transparent text-sm font-medium outline-none text-slate-300"/>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] hover:opacity-90 text-white font-black px-6 py-2.5 rounded-xl shadow-lg shadow-violet-900/50 text-sm">
                    <span class="material-symbols-outlined">search</span>Rechercher
                </button>
                <span class="text-slate-500 text-sm">ou</span>
                <a href="{{ route('passenger.showtrips') }}" class="inline-flex items-center gap-2 border-2 border-[#E8470A]/40 text-[#F97316] hover:bg-orange-500/10 font-bold px-5 py-2.5 rounded-xl text-sm">
                    <span class="material-symbols-outlined text-lg">hail</span>Lancer une demande
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Réservations récentes --}}
        <div class="lg:col-span-2 rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-violet-100 dark:border-violet-900/20 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-900/20 dark:to-transparent border-b border-violet-100 dark:border-violet-900/20">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#6C2BD9] text-base">bookmark</span>
                    </div>
                    <h3 class="font-black text-base">Mes réservations</h3>
                </div>
                <a href="{{ route('passenger.my-requests') }}" class="text-[#6C2BD9] text-sm font-bold hover:underline">Voir tout</a>
            </div>
            <div class="divide-y divide-violet-50 dark:divide-white/5">
                @forelse($recentReservations as $r)
                @php
                    $isConfirmed = $r->status === 'accepted';
                    $driver = $r->driverTrip?->driver;
                @endphp
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-violet-50/50 dark:hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ $isConfirmed ? 'bg-violet-100 dark:bg-violet-500/15' : 'bg-amber-100 dark:bg-amber-500/15' }}">
                        <span class="material-symbols-outlined text-xl {{ $isConfirmed ? 'text-[#6C2BD9]' : 'text-amber-500' }}">
                            {{ $isConfirmed ? 'check_circle' : 'schedule' }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm">{{ $r->departure_city }} → {{ $r->arrival_city }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($r->requested_date)->translatedFormat('d M Y') }}
                            @if($driver) · {{ $driver->first_name }} {{ $driver->last_name }} @endif
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($r->budget_max)
                        <p class="font-black text-sm text-[#6C2BD9]">{{ number_format($r->budget_max, 0, ',', ' ') }} FCFA</p>
                        @endif
                        <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full
                                     {{ $isConfirmed ? 'bg-violet-100 text-[#6C2BD9] dark:bg-violet-500/20' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
                            {{ $isConfirmed ? 'Confirmé' : 'En attente' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-slate-400">Aucune réservation pour l'instant.</p>
                    <a href="{{ route('passenger.trips') }}" class="inline-flex items-center gap-1 mt-3 text-xs font-bold text-[#6C2BD9] hover:underline">
                        <span class="material-symbols-outlined text-sm">search</span>Parcourir les trajets
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <div class="flex flex-col gap-4">
            {{-- Demande en cours --}}
            @if($currentRequest)
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-violet-100 dark:border-violet-900/20 shadow-sm">
                <div class="flex items-center gap-2 px-5 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-900/20 dark:to-transparent border-b border-violet-100 dark:border-violet-900/20">
                    <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#6C2BD9] text-base">hail</span>
                    </div>
                    <h3 class="font-black text-base flex-1">Ma demande en cours</h3>
                    <span class="flex items-center gap-1 text-xs font-bold text-[#E8470A]">
                        <span class="w-2 h-2 rounded-full bg-[#E8470A] animate-pulse"></span>En attente
                    </span>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col items-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#6C2BD9] border-2 border-violet-200"></span>
                            <span class="w-0.5 h-6 bg-slate-200 dark:bg-white/10"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-[#E8470A] border-2 border-orange-200"></span>
                        </div>
                        <div class="flex flex-col gap-3">
                            <p class="text-sm font-bold leading-none">{{ $currentRequest->departure_city }}</p>
                            <p class="text-sm font-bold leading-none">{{ $currentRequest->arrival_city }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/10 font-semibold text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            {{ \Carbon\Carbon::parse($currentRequest->requested_date)->translatedFormat('d M') }}
                            à {{ substr($currentRequest->requested_time, 0, 5) }}
                        </span>
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/10 font-semibold text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined text-sm">group</span>{{ $currentRequest->passengers }} pers.
                        </span>
                        @if($currentRequest->budget_max)
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-violet-100 dark:bg-violet-500/20 font-bold text-[#6C2BD9]">
                            <span class="material-symbols-outlined text-sm">payments</span>{{ number_format($currentRequest->budget_max, 0, ',', ' ') }} FCFA max
                        </span>
                        @endif
                    </div>
                    @if($currentRequest->expires_at)
                    <p class="flex items-center gap-1 text-xs text-[#E8470A] font-semibold">
                        <span class="material-symbols-outlined text-sm">timer</span>
                        Expire {{ $currentRequest->expires_at->diffForHumans() }}
                    </p>
                    @endif
                    <form method="POST" action="{{ route('passenger.requests.cancel', $currentRequest->id) }}"
                          onsubmit="return confirm('Annuler cette demande ?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full py-2 rounded-xl border-2 border-red-200 dark:border-red-500/30 text-red-500 text-xs font-bold hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            Annuler la demande
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Historique --}}
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-violet-100 dark:border-violet-900/20 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-900/20 dark:to-transparent border-b border-violet-100 dark:border-violet-900/20">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-500 text-base">history</span>
                        </div>
                        <h3 class="font-black text-base">Historique</h3>
                    </div>
                    <a href="{{ route('passenger.my-requests') }}" class="text-[#6C2BD9] text-sm font-bold hover:underline">Voir tout</a>
                </div>
                @php
                    $historyColors = ['bg-violet-100 dark:bg-violet-500/10 text-[#6C2BD9]','bg-blue-100 dark:bg-blue-500/10 text-blue-500','bg-orange-100 dark:bg-orange-500/10 text-[#E8470A]','bg-violet-100 dark:bg-violet-500/10 text-[#8B5CF6]'];
                @endphp
                <div class="divide-y divide-violet-50 dark:divide-white/5">
                    @forelse($history as $h)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-violet-50/50 dark:hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 rounded-lg {{ $historyColors[$loop->index % 4] }} flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-base">route</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-xs">{{ $h->departure_city }} → {{ $h->arrival_city }}</p>
                            <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($h->requested_date)->translatedFormat('d M Y') }}</p>
                        </div>
                        @if($h->budget_max)
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex-shrink-0">-{{ number_format($h->budget_max, 0, ',', ' ') }} FCFA</p>
                        @endif
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-xs text-slate-400">Aucun historique</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
