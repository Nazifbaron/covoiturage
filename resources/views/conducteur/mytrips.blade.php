@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                      flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Mes trajets</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Gérez vos trajets publiés</p>
            </div>
        </div>
        <a href="{{ route('driver.create-tips') }}"
           class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                  font-black px-4 py-2.5 rounded-xl transition-all shadow-md shadow-primary/20 text-sm">
            <span class="material-symbols-outlined text-lg">add</span>
            <span class="hidden sm:inline">Nouveau trajet</span>
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="grid grid-cols-4 gap-3">
        @foreach([
            ['label' => 'Total',     'value' => $counts['total'],     'icon' => 'route',            'color' => 'text-slate-500 dark:text-slate-400',  'bg' => 'bg-slate-100 dark:bg-white/5'],
            ['label' => 'Planifiés', 'value' => $counts['scheduled'], 'icon' => 'schedule',         'color' => 'text-blue-500',   'bg' => 'bg-blue-50 dark:bg-blue-500/10'],
            ['label' => 'Terminés',  'value' => $counts['completed'], 'icon' => 'check_circle',     'color' => 'text-primary',    'bg' => 'bg-primary/10'],
            ['label' => 'Annulés',   'value' => $counts['cancelled'], 'icon' => 'cancel',           'color' => 'text-red-400',    'bg' => 'bg-red-50 dark:bg-red-500/10'],
        ] as $stat)
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-4 flex flex-col items-center gap-2 text-center">
            <div class="w-9 h-9 rounded-xl {{ $stat['bg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined {{ $stat['color'] }} text-xl">{{ $stat['icon'] }}</span>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ $stat['value'] }}</p>
            <p class="text-xs font-semibold text-slate-400">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Liste des trajets ── --}}
    @if($trips->isEmpty())
        {{-- État vide --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary text-3xl">route</span>
            </div>
            <p class="font-black text-slate-900 dark:text-white text-lg">Aucun trajet publié</p>
            <p class="text-sm text-slate-400 mt-1 mb-6">Publiez votre premier trajet pour commencer à transporter des passagers.</p>
            <a href="{{ route('driver.create-tips') }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                      font-black px-6 py-3 rounded-xl transition-all shadow-md shadow-primary/20 text-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Publier un trajet
            </a>
        </div>

    @else
        <div class="space-y-3">
            @foreach($trips as $trip)

            {{-- Couleur & icône selon statut --}}
            @php
                $statusConfig = [
                    'scheduled' => ['label' => 'Planifié',  'icon' => 'schedule',      'pill' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/20'],
                    'ongoing'   => ['label' => 'En cours',  'icon' => 'directions_car', 'pill' => 'bg-primary/10 text-primary border-primary/20'],
                    'completed' => ['label' => 'Terminé',   'icon' => 'check_circle',  'pill' => 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10'],
                    'cancelled' => ['label' => 'Annulé',    'icon' => 'cancel',        'pill' => 'bg-red-50 dark:bg-red-500/10 text-red-500 border-red-100 dark:border-red-500/20'],
                ];
                $sc = $statusConfig[$trip->status] ?? $statusConfig['scheduled'];
            @endphp

            <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden
                        {{ $trip->status === 'cancelled' ? 'opacity-60' : '' }}">

                {{-- Barre colorée top --}}
                <div class="h-1 w-full
                    {{ $trip->status === 'scheduled' ? 'bg-blue-400' : '' }}
                    {{ $trip->status === 'ongoing'   ? 'bg-primary' : '' }}
                    {{ $trip->status === 'completed' ? 'bg-slate-300 dark:bg-white/10' : '' }}
                    {{ $trip->status === 'cancelled' ? 'bg-red-300 dark:bg-red-500/30' : '' }}">
                </div>

                <div class="p-5">
                    {{-- Ligne 1 : trajet + statut --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Icône départ/arrivée --}}
                            <div class="flex flex-col items-center gap-1 flex-shrink-0">
                                <div class="w-8 h-8 rounded-xl bg-primary/10 border border-primary/20
                                            flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary" style="font-size:16px">trip_origin</span>
                                </div>
                                <div class="w-px h-4 bg-slate-200 dark:bg-white/10"></div>
                                <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10
                                            flex items-center justify-center">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:16px">location_on</span>
                                </div>
                            </div>

                            {{-- Villes --}}
                            <div class="min-w-0">
                                <p class="font-black text-slate-900 dark:text-white text-base truncate">
                                    {{ $trip->departure_city }}
                                </p>
                                <p class="text-xs text-slate-400 font-medium my-1">→</p>
                                <p class="font-black text-slate-700 dark:text-slate-300 text-base truncate">
                                    {{ $trip->arrival_city }}
                                </p>
                            </div>
                        </div>

                        {{-- Badge statut --}}
                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                     text-xs font-black border {{ $sc['pill'] }}">
                            <span class="material-symbols-outlined" style="font-size:13px">{{ $sc['icon'] }}</span>
                            {{ $sc['label'] }}
                        </span>
                    </div>

                    {{-- Ligne 2 : infos --}}
                    <div class="mt-4 flex flex-wrap gap-3">
                        {{-- Date --}}
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined" style="font-size:15px">calendar_today</span>
                            {{ \Carbon\Carbon::parse($trip->departure_date)->translatedFormat('D d M Y') }}
                        </div>
                        {{-- Heure --}}
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined" style="font-size:15px">schedule</span>
                            {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                        </div>
                        {{-- Places --}}
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined" style="font-size:15px">airline_seat_recline_normal</span>
                            {{ $trip->seats_available }}/{{ $trip->seats_total }} place{{ $trip->seats_total > 1 ? 's' : '' }}
                        </div>
                        {{-- Prix --}}
                        <div class="flex items-center gap-1.5 text-xs font-black text-primary">
                            <span class="material-symbols-outlined" style="font-size:15px">payments</span>
                            {{ number_format($trip->price_per_seat, 0, ',', ' ') }} FCFA
                        </div>
                    </div>

                    {{-- Options --}}
                    @if($trip->luggage_allowed || $trip->pets_allowed || $trip->silent_ride || $trip->female_only)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($trip->luggage_allowed)
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-white/5
                                     text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined" style="font-size:13px">luggage</span> Bagages
                        </span>
                        @endif
                        @if($trip->pets_allowed)
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-white/5
                                     text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined" style="font-size:13px">pets</span> Animaux
                        </span>
                        @endif
                        @if($trip->silent_ride)
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-white/5
                                     text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined" style="font-size:13px">volume_off</span> Silence
                        </span>
                        @endif
                        @if($trip->female_only)
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-pink-50 dark:bg-pink-500/10
                                     text-xs font-semibold text-pink-500">
                            <span class="material-symbols-outlined" style="font-size:13px">female</span> Femmes seult.
                        </span>
                        @endif
                    </div>
                    @endif

                    {{-- Actions --}}
                    @if($trip->status === 'scheduled')
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <a href="{{ route('driver.requests') }}"
                           class="flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400
                                  hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-base">groups</span>
                            Voir les demandes
                        </a>
                        <form method="POST" action="{{ route('driver.trips.cancel', $trip->id) }}"
                              onsubmit="return confirm('Annuler ce trajet ?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="flex items-center gap-1.5 text-xs font-bold text-red-400
                                           hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-base">cancel</span>
                                Annuler
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        @if($trips->hasPages())
        <div class="flex items-center justify-center gap-2">
            {{-- Précédent --}}
            @if($trips->onFirstPage())
                <span class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                             flex items-center justify-center opacity-30 cursor-not-allowed">
                    <span class="material-symbols-outlined text-slate-400 text-xl">chevron_left</span>
                </span>
            @else
                <a href="{{ $trips->previousPageUrl() }}"
                   class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                          flex items-center justify-center hover:border-primary/30 hover:bg-primary/5 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">chevron_left</span>
                </a>
            @endif

            {{-- Pages --}}
            @foreach($trips->getUrlRange(max(1, $trips->currentPage()-2), min($trips->lastPage(), $trips->currentPage()+2)) as $page => $url)
                @if($page === $trips->currentPage())
                    <span class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center
                                 text-background-dark font-black text-sm shadow-md shadow-primary/20">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                              flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-sm
                              hover:border-primary/30 hover:bg-primary/5 transition-all shadow-sm">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Suivant --}}
            @if($trips->hasMorePages())
                <a href="{{ $trips->nextPageUrl() }}"
                   class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                          flex items-center justify-center hover:border-primary/30 hover:bg-primary/5 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">chevron_right</span>
                </span>
            @else
                <span class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                             flex items-center justify-center opacity-30 cursor-not-allowed">
                    <span class="material-symbols-outlined text-slate-400 text-xl">chevron_right</span>
                </span>
            @endif
        </div>
        @endif
    @endif

</div>
@endsection
