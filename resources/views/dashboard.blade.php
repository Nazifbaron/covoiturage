@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- ══════════════════════════════════════
         BANNIÈRE DE BIENVENUE
    ══════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl p-6
                bg-gradient-to-br from-[#0b1a0f] via-[#102215] to-[#0f2d17]
                flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-primary/10 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 w-40 h-40 rounded-full bg-primary/5 translate-y-1/2 pointer-events-none"></div>

        <div class="relative z-10">
            <p class="text-xs font-bold text-primary/60 uppercase tracking-widest mb-1">
                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </p>
            <h2 class="text-2xl font-black text-white">
                Bonjour, {{ Auth::user()->first_name }} 👋
            </h2>
            <p class="text-slate-400 text-sm mt-1">
                @if(Auth::user()->role === 'driver')
                    Gérez vos trajets et suivez vos gains en temps réel.
                @else
                    Trouvez votre prochain trajet ou consultez vos réservations.
                @endif
            </p>
        </div>

        @if(Auth::user()->role === 'driver')
            <a href=""
               class="relative z-10 flex-shrink-0 inline-flex items-center gap-2
                      bg-primary hover:bg-primary/90 text-background-dark
                      font-black px-5 py-3 rounded-xl transition-all
                      shadow-lg shadow-primary/30 text-sm">
                <span class="material-symbols-outlined text-xl">add_circle</span>
                Publier un trajet
            </a>
        @else
            <div class="relative z-10 flex flex-col sm:flex-row gap-2 flex-shrink-0">
                <a href="#"
                   class="inline-flex items-center gap-2
                          bg-white/10 hover:bg-white/20 text-white border border-white/20
                          font-bold px-5 py-3 rounded-xl transition-all text-sm">
                    <span class="material-symbols-outlined text-xl">search</span>
                    Rechercher
                </a>
                <a href=""
                   class="inline-flex items-center gap-2
                          bg-primary hover:bg-primary/90 text-background-dark
                          font-black px-5 py-3 rounded-xl transition-all
                          shadow-lg shadow-primary/30 text-sm">
                    <span class="material-symbols-outlined text-xl">hail</span>
                    Demander un trajet
                </a>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════
         STATS CARDS
    ══════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        @if(Auth::user()->role === 'driver')

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-emerald-500 to-green-600
                        shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">payments</span>
                    </div>
                    <span class="text-xs font-bold text-white bg-white/20 px-2 py-0.5 rounded-full">+12%</span>
                </div>
                <p class="text-xl font-black text-white">54 500 <span class="text-sm font-semibold text-white/70">FCFA</span></p>
                <p class="text-xs text-white/70 mt-1 font-medium">Gains ce mois</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-blue-500 to-indigo-600
                        shadow-lg shadow-blue-200 dark:shadow-blue-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-white">directions_car</span>
                </div>
                <p class="text-xl font-black text-white">8</p>
                <p class="text-xs text-white/70 mt-1 font-medium">Trajets publiés</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-orange-400 to-amber-500
                        shadow-lg shadow-orange-200 dark:shadow-orange-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">pending_actions</span>
                    </div>
                    <span class="w-6 h-6 rounded-full bg-white text-orange-500 text-xs font-black flex items-center justify-center">3</span>
                </div>
                <p class="text-xl font-black text-white">3</p>
                <p class="text-xs text-white/70 mt-1 font-medium">Demandes en attente</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-violet-500 to-purple-600
                        shadow-lg shadow-violet-200 dark:shadow-violet-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-white">star</span>
                </div>
                <p class="text-xl font-black text-white">4.8 <span class="text-sm font-semibold text-white/70">/ 5</span></p>
                <p class="text-xs text-white/70 mt-1 font-medium">Note moyenne</p>
            </div>

        @else

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-emerald-500 to-green-600
                        shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-white">bookmark</span>
                </div>
                <p class="text-xl font-black text-white">4</p>
                <p class="text-xs text-white/70 mt-1 font-medium">Réservations actives</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-blue-500 to-indigo-600
                        shadow-lg shadow-blue-200 dark:shadow-blue-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-white">history</span>
                </div>
                <p class="text-xl font-black text-white">23</p>
                <p class="text-xs text-white/70 mt-1 font-medium">Trajets effectués</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-orange-400 to-amber-500
                        shadow-lg shadow-orange-200 dark:shadow-orange-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">savings</span>
                    </div>
                    <span class="text-xs font-bold text-white bg-white/20 px-2 py-0.5 rounded-full">-34%</span>
                </div>
                <p class="text-xl font-black text-white">12 800 <span class="text-sm font-semibold text-white/70">FCFA</span></p>
                <p class="text-xs text-white/70 mt-1 font-medium">Économies ce mois</p>
            </div>

            {{-- Demande en cours --}}
            <div class="relative overflow-hidden rounded-2xl p-5
                        bg-gradient-to-br from-violet-500 to-purple-600
                        shadow-lg shadow-violet-200 dark:shadow-violet-900/30
                        hover:-translate-y-1 transition-all duration-200">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/3"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">hail</span>
                    </div>
                    <span class="flex items-center gap-1 text-xs font-bold text-white bg-white/20 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-300 animate-pulse"></span> Live
                    </span>
                </div>
                <p class="text-xl font-black text-white">1</p>
                <p class="text-xs text-white/70 mt-1 font-medium">Demande en cours</p>
            </div>

        @endif
    </div>

    {{-- ══════════════════════════════════════
         CONTENU PRINCIPAL
    ══════════════════════════════════════ --}}

    @if(Auth::user()->role === 'driver')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Trajets publiés --}}
            <div class="lg:col-span-2 rounded-2xl overflow-hidden
                        bg-white dark:bg-card-dark
                        border border-slate-100 dark:border-primary/10
                        shadow-sm">
                <div class="flex items-center justify-between px-5 py-4
                            bg-gradient-to-r from-slate-50 to-white
                            dark:from-white/5 dark:to-transparent
                            border-b border-slate-100 dark:border-primary/10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-500 text-base">directions_car</span>
                        </div>
                        <h3 class="font-black text-base">Mes trajets publiés</h3>
                    </div>
                    <a href="#" class="text-primary text-sm font-bold hover:underline">Voir tout</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5">
                    @foreach([
                        ['from' => 'Cotonou',    'to' => 'Porto-Novo',    'date' => "Aujourd'hui 07:30", 'seats' => 2, 'price' => '1 500', 'status' => 'active'],
                        ['from' => 'Cotonou',    'to' => 'Abomey-Calavi', 'date' => 'Demain 08:00',      'seats' => 3, 'price' => '800',   'status' => 'active'],
                        ['from' => 'Porto-Novo', 'to' => 'Cotonou',       'date' => '08 Mar 17:00',      'seats' => 0, 'price' => '1 500', 'status' => 'full'],
                    ] as $i => $trajet)
                    @php $colors = ['bg-blue-50 dark:bg-blue-500/10','bg-emerald-50 dark:bg-emerald-500/10','bg-red-50 dark:bg-red-500/10']; @endphp
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <div class="w-10 h-10 rounded-xl {{ $colors[$i] }} flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-xl
                                {{ $trajet['status'] === 'full' ? 'text-red-400' : ($i === 0 ? 'text-blue-500' : 'text-emerald-500') }}">
                                directions_car
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm">{{ $trajet['from'] }} → {{ $trajet['to'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $trajet['date'] }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-black text-sm text-emerald-600 dark:text-primary">{{ $trajet['price'] }} FCFA</p>
                            <span class="text-xs font-semibold {{ $trajet['status'] === 'full' ? 'text-red-400' : 'text-emerald-500' }}">
                                {{ $trajet['status'] === 'full' ? 'Complet' : $trajet['seats'].' places' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Colonne droite conducteur --}}
            <div class="flex flex-col gap-4">

                {{-- Demandes de réservation --}}
                <div class="rounded-2xl overflow-hidden
                            bg-white dark:bg-card-dark
                            border border-slate-100 dark:border-primary/10
                            shadow-sm">
                    <div class="flex items-center justify-between px-5 py-4
                                bg-gradient-to-r from-orange-50 to-amber-50
                                dark:from-orange-500/10 dark:to-transparent
                                border-b border-orange-100 dark:border-primary/10">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-orange-500 text-base">pending_actions</span>
                            </div>
                            <h3 class="font-black text-base">Demandes</h3>
                        </div>
                        <span class="bg-orange-500 text-white text-xs font-black px-2.5 py-1 rounded-full">3</span>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach([
                            ['name' => 'Amine D.', 'trajet' => 'Cotonou → Porto-Novo', 'time' => 'Il y a 5 min'],
                            ['name' => 'Fatou K.', 'trajet' => 'Cotonou → Calavi',     'time' => 'Il y a 20 min'],
                            ['name' => 'Kofi A.',  'trajet' => 'Cotonou → Porto-Novo', 'time' => 'Il y a 1h'],
                        ] as $demande)
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-white text-base">person</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-sm">{{ $demande['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $demande['trajet'] }}</p>
                                </div>
                                <span class="text-xs text-slate-400 flex-shrink-0">{{ $demande['time'] }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 py-1.5 rounded-lg bg-emerald-500 text-white text-xs font-bold hover:bg-emerald-600 transition-colors shadow-sm shadow-emerald-200">
                                    Accepter
                                </button>
                                <button class="flex-1 py-1.5 rounded-lg bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400 text-xs font-bold hover:bg-red-50 hover:text-red-500 transition-colors">
                                    Refuser
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ✨ Courses à proximité (demandes passagers) --}}
                <div class="rounded-2xl overflow-hidden
                            bg-white dark:bg-card-dark
                            border border-slate-100 dark:border-primary/10
                            shadow-sm">
                    <div class="flex items-center justify-between px-5 py-4
                                bg-gradient-to-r from-violet-50 to-white
                                dark:from-violet-500/10 dark:to-transparent
                                border-b border-violet-100 dark:border-primary/10">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-violet-500 text-base">hail</span>
                            </div>
                            <h3 class="font-black text-base">Courses à proximité</h3>
                        </div>
                        <span class="bg-violet-500 text-white text-xs font-black px-2.5 py-1 rounded-full">2</span>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach([
                            ['name' => 'Séna M.',    'from' => 'Cotonou', 'to' => 'Porto-Novo', 'time' => 'Auj. 09:00', 'budget' => '1 500', 'passengers' => 1, 'expires' => '2h rest.'],
                            ['name' => 'Ibrahim T.', 'from' => 'Calavi',  'to' => 'Cotonou',    'time' => 'Auj. 11:30', 'budget' => '1 000', 'passengers' => 2, 'expires' => '45 min'],
                        ] as $course)
                        <div class="px-5 py-4 space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-white text-base">person</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <p class="font-bold text-sm">{{ $course['name'] }}</p>
                                        <span class="text-xs text-orange-500 font-semibold flex items-center gap-0.5 flex-shrink-0">
                                            <span class="material-symbols-outlined text-sm">timer</span>{{ $course['expires'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-0.5">
                                        {{ $course['from'] }} → {{ $course['to'] }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="flex items-center gap-0.5 text-xs text-slate-500">
                                            <span class="material-symbols-outlined text-sm">schedule</span>{{ $course['time'] }}
                                        </span>
                                        <span class="flex items-center gap-0.5 text-xs text-slate-500">
                                            <span class="material-symbols-outlined text-sm">group</span>{{ $course['passengers'] }} pers.
                                        </span>
                                        <span class="flex items-center gap-0.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                            <span class="material-symbols-outlined text-sm">payments</span>{{ $course['budget'] }} FCFA
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 py-1.5 rounded-lg bg-violet-500 text-white text-xs font-bold hover:bg-violet-600 transition-colors">
                                    Accepter
                                </button>
                                <button class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-white/10 text-slate-500 text-xs font-bold hover:bg-red-50 hover:text-red-500 transition-colors">
                                    Ignorer
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-5 py-3 border-t border-slate-100 dark:border-white/5">
                        <a href=""
                           class="text-xs font-bold text-violet-500 hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                            Voir toutes les demandes
                        </a>
                    </div>
                </div>

            </div>
        </div>

    @else

        {{-- Recherche rapide --}}
        <div class="rounded-2xl p-5
                    bg-gradient-to-br from-slate-800 to-slate-900
                    dark:from-card-dark dark:to-background-dark
                    shadow-lg">
            <h3 class="font-black text-base text-white mb-4">Rechercher un trajet</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Départ</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                        <span class="material-symbols-outlined text-primary text-lg">trip_origin</span>
                        <input type="text" placeholder="Ex: Cotonou"
                               class="flex-1 bg-transparent text-sm font-medium outline-none placeholder-slate-500 text-white"/>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Destination</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                        <span class="material-symbols-outlined text-red-400 text-lg">location_on</span>
                        <input type="text" placeholder="Ex: Porto-Novo"
                               class="flex-1 bg-transparent text-sm font-medium outline-none placeholder-slate-500 text-white"/>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Date</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                        <span class="material-symbols-outlined text-slate-400 text-lg">calendar_today</span>
                        <input type="date"
                               class="flex-1 bg-transparent text-sm font-medium outline-none text-slate-300"/>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button class="inline-flex items-center gap-2
                               bg-primary hover:bg-primary/90 text-background-dark
                               font-black px-6 py-2.5 rounded-xl transition-all
                               shadow-lg shadow-primary/30 text-sm">
                    <span class="material-symbols-outlined">search</span>
                    Rechercher
                </button>
                <span class="text-slate-500 text-sm">ou</span>
                <a href="{{route('passenger.showtrips')}}"
                   class="inline-flex items-center gap-2
                          border-2 border-primary/40 text-primary hover:bg-primary/10
                          font-bold px-5 py-2.5 rounded-xl transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">hail</span>
                    Lancer une demande de course
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Réservations --}}
            <div class="lg:col-span-2 rounded-2xl overflow-hidden
                        bg-white dark:bg-card-dark
                        border border-slate-100 dark:border-primary/10
                        shadow-sm">
                <div class="flex items-center justify-between px-5 py-4
                            bg-gradient-to-r from-emerald-50 to-white
                            dark:from-white/5 dark:to-transparent
                            border-b border-emerald-100 dark:border-primary/10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-500 text-base">bookmark</span>
                        </div>
                        <h3 class="font-black text-base">Mes réservations</h3>
                    </div>
                    <a href="#" class="text-primary text-sm font-bold hover:underline">Voir tout</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5">
                    @foreach([
                        ['from' => 'Cotonou',    'to' => 'Porto-Novo',    'date' => "Aujourd'hui 07:30", 'driver' => 'Koffi M.',   'price' => '1 500', 'status' => 'confirmed'],
                        ['from' => 'Cotonou',    'to' => 'Abomey-Calavi', 'date' => 'Demain 08:00',      'driver' => 'Aïcha B.',   'price' => '800',   'status' => 'pending'],
                        ['from' => 'Porto-Novo', 'to' => 'Cotonou',       'date' => '08 Mar 17:00',      'driver' => 'Séverin D.', 'price' => '1 500', 'status' => 'confirmed'],
                    ] as $reservation)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <div class="w-10 h-10 rounded-xl
                            {{ $reservation['status'] === 'confirmed' ? 'bg-emerald-100 dark:bg-emerald-500/15' : 'bg-amber-100 dark:bg-amber-500/15' }}
                            flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-xl
                                {{ $reservation['status'] === 'confirmed' ? 'text-emerald-500' : 'text-amber-500' }}">
                                {{ $reservation['status'] === 'confirmed' ? 'check_circle' : 'schedule' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm">{{ $reservation['from'] }} → {{ $reservation['to'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ $reservation['date'] }} · {{ $reservation['driver'] }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-black text-sm text-emerald-600 dark:text-primary">{{ $reservation['price'] }} FCFA</p>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $reservation['status'] === 'confirmed'
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                                    : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
                                {{ $reservation['status'] === 'confirmed' ? 'Confirmé' : 'En attente' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Colonne droite passager --}}
            <div class="flex flex-col gap-4">

                {{-- Ma demande en cours --}}
                <div class="rounded-2xl overflow-hidden
                            bg-white dark:bg-card-dark
                            border border-violet-100 dark:border-violet-500/20
                            shadow-sm">
                    <div class="flex items-center gap-2 px-5 py-4
                                bg-gradient-to-r from-violet-50 to-white
                                dark:from-violet-500/10 dark:to-transparent
                                border-b border-violet-100 dark:border-primary/10">
                        <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-violet-500 text-base">hail</span>
                        </div>
                        <h3 class="font-black text-base flex-1">Ma demande en cours</h3>
                        <span class="flex items-center gap-1 text-xs font-bold text-orange-500">
                            <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                            En attente
                        </span>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary border-2 border-primary/30"></span>
                                <span class="w-0.5 h-6 bg-slate-200 dark:bg-white/10"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400 border-2 border-red-200"></span>
                            </div>
                            <div class="flex flex-col gap-3">
                                <p class="text-sm font-bold leading-none">Cotonou <span class="text-slate-400 font-medium text-xs">Cadjèhoun</span></p>
                                <p class="text-sm font-bold leading-none">Porto-Novo <span class="text-slate-400 font-medium text-xs">Gare centrale</span></p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/10 font-semibold text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-sm">schedule</span> Auj. 09:00
                            </span>
                            <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/10 font-semibold text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-sm">group</span> 1 pers.
                            </span>
                            <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 font-bold text-emerald-700 dark:text-emerald-400">
                                <span class="material-symbols-outlined text-sm">payments</span> 1 500 FCFA max
                            </span>
                        </div>
                        <p class="flex items-center gap-1 text-xs text-orange-500 font-semibold">
                            <span class="material-symbols-outlined text-sm">timer</span>
                            Expire dans 2h 14min
                        </p>
                        <button class="w-full py-2 rounded-xl border-2 border-red-200 dark:border-red-500/30 text-red-500 text-xs font-bold
                                       hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            Annuler la demande
                        </button>
                    </div>
                </div>

                {{-- Historique --}}
                <div class="rounded-2xl overflow-hidden
                            bg-white dark:bg-card-dark
                            border border-slate-100 dark:border-primary/10
                            shadow-sm">
                    <div class="flex items-center justify-between px-5 py-4
                                bg-gradient-to-r from-slate-50 to-white
                                dark:from-white/5 dark:to-transparent
                                border-b border-slate-100 dark:border-primary/10">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-white/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-slate-500 text-base">history</span>
                            </div>
                            <h3 class="font-black text-base">Historique</h3>
                        </div>
                        <a href="#" class="text-primary text-sm font-bold hover:underline">Voir tout</a>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach([
                            ['from' => 'Cotonou',    'to' => 'Porto-Novo', 'date' => '03 Mar', 'price' => '1 500', 'color' => 'bg-blue-100 text-blue-500'],
                            ['from' => 'Calavi',     'to' => 'Cotonou',    'date' => '01 Mar', 'price' => '800',   'color' => 'bg-violet-100 text-violet-500'],
                            ['from' => 'Cotonou',    'to' => 'Ouidah',     'date' => '28 Fév', 'price' => '2 000', 'color' => 'bg-emerald-100 text-emerald-500'],
                            ['from' => 'Porto-Novo', 'to' => 'Cotonou',    'date' => '25 Fév', 'price' => '1 500', 'color' => 'bg-orange-100 text-orange-500'],
                        ] as $h)
                        <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <div class="w-8 h-8 rounded-lg {{ $h['color'] }} dark:bg-white/10 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-base">route</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-xs">{{ $h['from'] }} → {{ $h['to'] }}</p>
                                <p class="text-xs text-slate-400">{{ $h['date'] }}</p>
                            </div>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex-shrink-0">
                                -{{ $h['price'] }} FCFA
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    @endif

</div>
@endsection
