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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @if(Auth::user()->role === 'driver')
            @foreach([
                ['icon'=>'payments',        'value'=>'54 500', 'unit'=>'FCFA', 'label'=>'Gains ce mois',       'from'=>'from-[#6C2BD9]', 'to'=>'to-[#8B5CF6]', 'badge'=>'+12%'],
                ['icon'=>'directions_car',  'value'=>'8',      'unit'=>'',     'label'=>'Trajets publiés',     'from'=>'from-blue-500',  'to'=>'to-indigo-600', 'badge'=>''],
                ['icon'=>'pending_actions', 'value'=>'3',      'unit'=>'',     'label'=>'Demandes en attente', 'from'=>'from-[#E8470A]', 'to'=>'to-[#F97316]', 'badge'=>'3'],
                ['icon'=>'star',            'value'=>'4.8',    'unit'=>'/ 5',  'label'=>'Note moyenne',        'from'=>'from-[#4C1D95]', 'to'=>'to-[#6C2BD9]', 'badge'=>''],
            ] as $s)
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
        @else
            @foreach([
                ['icon'=>'bookmark',  'value'=>'4',      'unit'=>'',     'label'=>'Réservations actives', 'from'=>'from-[#6C2BD9]', 'to'=>'to-[#8B5CF6]', 'badge'=>''],
                ['icon'=>'history',   'value'=>'23',     'unit'=>'',     'label'=>'Trajets effectués',    'from'=>'from-blue-500',  'to'=>'to-indigo-600', 'badge'=>''],
                ['icon'=>'savings',   'value'=>'12 800', 'unit'=>'FCFA', 'label'=>'Économies ce mois',    'from'=>'from-[#E8470A]', 'to'=>'to-[#F97316]', 'badge'=>'-34%'],
                ['icon'=>'hail',      'value'=>'1',      'unit'=>'',     'label'=>'Demande en cours',     'from'=>'from-[#4C1D95]', 'to'=>'to-[#6C2BD9]', 'badge'=>'Live'],
            ] as $s)
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
        @endif
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
                @foreach([
                    ['from'=>'Cotonou','to'=>'Porto-Novo','date'=>"Aujourd'hui 07:30",'seats'=>2,'price'=>'1 500','status'=>'active'],
                    ['from'=>'Cotonou','to'=>'Abomey-Calavi','date'=>'Demain 08:00','seats'=>3,'price'=>'800','status'=>'active'],
                    ['from'=>'Porto-Novo','to'=>'Cotonou','date'=>'08 Mar 17:00','seats'=>0,'price'=>'1 500','status'=>'full'],
                ] as $i => $t)
                @php $ic = ['bg-violet-50 dark:bg-violet-500/10','bg-blue-50 dark:bg-blue-500/10','bg-red-50 dark:bg-red-500/10']; $tc = ['text-[#6C2BD9]','text-blue-500','text-red-400']; @endphp
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-violet-50/50 dark:hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 rounded-xl {{ $ic[$i] }} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl {{ $tc[$i] }}">directions_car</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm">{{ $t['from'] }} → {{ $t['to'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $t['date'] }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-black text-sm text-[#6C2BD9]">{{ $t['price'] }} FCFA</p>
                        <span class="text-xs font-semibold {{ $t['status']==='full' ? 'text-red-400' : 'text-[#6C2BD9]' }}">
                            {{ $t['status']==='full' ? 'Complet' : $t['seats'].' places' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="flex flex-col gap-4">
            {{-- Demandes --}}
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-orange-100 dark:border-orange-900/20 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-transparent border-b border-orange-100 dark:border-orange-900/20">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#E8470A] text-base">pending_actions</span>
                        </div>
                        <h3 class="font-black text-base">Demandes</h3>
                    </div>
                    <span class="bg-gradient-to-r from-[#E8470A] to-[#F97316] text-white text-xs font-black px-2.5 py-1 rounded-full">3</span>
                </div>
                <div class="divide-y divide-orange-50 dark:divide-white/5">
                    @foreach([
                        ['name'=>'Amine D.','trajet'=>'Cotonou → Porto-Novo','time'=>'Il y a 5 min'],
                        ['name'=>'Fatou K.','trajet'=>'Cotonou → Calavi','time'=>'Il y a 20 min'],
                        ['name'=>'Kofi A.','trajet'=>'Cotonou → Porto-Novo','time'=>'Il y a 1h'],
                    ] as $d)
                    <div class="px-5 py-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C2BD9] to-[#4C1D95] flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-white text-base">person</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-sm">{{ $d['name'] }}</p>
                                <p class="text-xs text-slate-500">{{ $d['trajet'] }}</p>
                            </div>
                            <span class="text-xs text-slate-400 flex-shrink-0">{{ $d['time'] }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button class="flex-1 py-1.5 rounded-lg bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] text-white text-xs font-bold hover:opacity-90 transition-opacity">Accepter</button>
                            <button class="flex-1 py-1.5 rounded-lg bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400 text-xs font-bold hover:bg-red-50 hover:text-red-500 transition-colors">Refuser</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            {{-- Courses à proximité --}}
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-violet-100 dark:border-violet-900/20 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-900/20 dark:to-transparent border-b border-violet-100 dark:border-violet-900/20">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#6C2BD9] text-base">hail</span>
                        </div>
                        <h3 class="font-black text-base">Courses à proximité</h3>
                    </div>
                    <span class="bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] text-white text-xs font-black px-2.5 py-1 rounded-full">2</span>
                </div>
                <div class="divide-y divide-violet-50 dark:divide-white/5">
                    @foreach([
                        ['name'=>'Séna M.','from'=>'Cotonou','to'=>'Porto-Novo','time'=>'Auj. 09:00','budget'=>'1 500','passengers'=>1,'expires'=>'2h rest.'],
                        ['name'=>'Ibrahim T.','from'=>'Calavi','to'=>'Cotonou','time'=>'Auj. 11:30','budget'=>'1 000','passengers'=>2,'expires'=>'45 min'],
                    ] as $c)
                    <div class="px-5 py-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C2BD9] to-[#4C1D95] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-white text-base">person</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="font-bold text-sm">{{ $c['name'] }}</p>
                                    <span class="text-xs text-[#E8470A] font-semibold flex items-center gap-0.5 flex-shrink-0">
                                        <span class="material-symbols-outlined text-sm">timer</span>{{ $c['expires'] }}
                                    </span>
                                </div>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $c['from'] }} → {{ $c['to'] }}</p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="flex items-center gap-0.5 text-xs text-slate-500"><span class="material-symbols-outlined text-sm">schedule</span>{{ $c['time'] }}</span>
                                    <span class="flex items-center gap-0.5 text-xs text-slate-500"><span class="material-symbols-outlined text-sm">group</span>{{ $c['passengers'] }} pers.</span>
                                    <span class="flex items-center gap-0.5 text-xs font-bold text-[#6C2BD9]"><span class="material-symbols-outlined text-sm">payments</span>{{ $c['budget'] }} FCFA</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="flex-1 py-1.5 rounded-lg bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] text-white text-xs font-bold hover:opacity-90">Accepter</button>
                            <button class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-white/10 text-slate-500 text-xs font-bold hover:bg-red-50 hover:text-red-500 transition-colors">Ignorer</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t border-violet-50 dark:border-white/5">
                    <a href="{{ route('driver.requests') }}" class="text-xs font-bold text-[#6C2BD9] hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">open_in_new</span>Voir toutes les demandes
                    </a>
                </div>
            </div>
        </div>
    </div>

    @else

    {{-- Recherche --}}
    <div class="rounded-2xl p-5 bg-gradient-to-br from-[#1a0a3e] via-[#2d1270] to-[#1a0a3e] shadow-lg shadow-violet-900/30">
        <h3 class="font-black text-base text-white mb-4">Rechercher un trajet</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @foreach([['label'=>'Départ','icon'=>'trip_origin','color'=>'text-[#8B5CF6]','placeholder'=>'Ex: Cotonou'],['label'=>'Destination','icon'=>'location_on','color'=>'text-[#F97316]','placeholder'=>'Ex: Porto-Novo']] as $f)
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-violet-300/70 uppercase tracking-wide">{{ $f['label'] }}</label>
                <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                    <span class="material-symbols-outlined {{ $f['color'] }} text-lg">{{ $f['icon'] }}</span>
                    <input type="text" placeholder="{{ $f['placeholder'] }}" class="flex-1 bg-transparent text-sm font-medium outline-none placeholder-slate-500 text-white"/>
                </div>
            </div>
            @endforeach
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-violet-300/70 uppercase tracking-wide">Date</label>
                <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-white/10 bg-white/10">
                    <span class="material-symbols-outlined text-slate-400 text-lg">calendar_today</span>
                    <input type="date" class="flex-1 bg-transparent text-sm font-medium outline-none text-slate-300"/>
                </div>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <button class="inline-flex items-center gap-2 bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] hover:opacity-90 text-white font-black px-6 py-2.5 rounded-xl shadow-lg shadow-violet-900/50 text-sm">
                <span class="material-symbols-outlined">search</span>Rechercher
            </button>
            <span class="text-slate-500 text-sm">ou</span>
            <a href="{{ route('passenger.showtrips') }}" class="inline-flex items-center gap-2 border-2 border-[#E8470A]/40 text-[#F97316] hover:bg-orange-500/10 font-bold px-5 py-2.5 rounded-xl text-sm">
                <span class="material-symbols-outlined text-lg">hail</span>Lancer une demande de course
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Réservations --}}
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
                @foreach([
                    ['from'=>'Cotonou','to'=>'Porto-Novo','date'=>"Aujourd'hui 07:30",'driver'=>'Koffi M.','price'=>'1 500','status'=>'confirmed'],
                    ['from'=>'Cotonou','to'=>'Abomey-Calavi','date'=>'Demain 08:00','driver'=>'Aïcha B.','price'=>'800','status'=>'pending'],
                    ['from'=>'Porto-Novo','to'=>'Cotonou','date'=>'08 Mar 17:00','driver'=>'Séverin D.','price'=>'1 500','status'=>'confirmed'],
                ] as $r)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-violet-50/50 dark:hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $r['status']==='confirmed' ? 'bg-violet-100 dark:bg-violet-500/15' : 'bg-amber-100 dark:bg-amber-500/15' }}">
                        <span class="material-symbols-outlined text-xl {{ $r['status']==='confirmed' ? 'text-[#6C2BD9]' : 'text-amber-500' }}">{{ $r['status']==='confirmed' ? 'check_circle' : 'schedule' }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm">{{ $r['from'] }} → {{ $r['to'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $r['date'] }} · {{ $r['driver'] }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-black text-sm text-[#6C2BD9]">{{ $r['price'] }} FCFA</p>
                        <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full {{ $r['status']==='confirmed' ? 'bg-violet-100 text-[#6C2BD9] dark:bg-violet-500/20' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
                            {{ $r['status']==='confirmed' ? 'Confirmé' : 'En attente' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-4">
            {{-- Demande en cours --}}
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
                            <p class="text-sm font-bold leading-none">Cotonou <span class="text-slate-400 font-medium text-xs">Cadjèhoun</span></p>
                            <p class="text-sm font-bold leading-none">Porto-Novo <span class="text-slate-400 font-medium text-xs">Gare centrale</span></p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/10 font-semibold text-slate-600 dark:text-slate-400"><span class="material-symbols-outlined text-sm">schedule</span>Auj. 09:00</span>
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/10 font-semibold text-slate-600 dark:text-slate-400"><span class="material-symbols-outlined text-sm">group</span>1 pers.</span>
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-violet-100 dark:bg-violet-500/20 font-bold text-[#6C2BD9]"><span class="material-symbols-outlined text-sm">payments</span>1 500 FCFA max</span>
                    </div>
                    <p class="flex items-center gap-1 text-xs text-[#E8470A] font-semibold"><span class="material-symbols-outlined text-sm">timer</span>Expire dans 2h 14min</p>
                    <button class="w-full py-2 rounded-xl border-2 border-red-200 dark:border-red-500/30 text-red-500 text-xs font-bold hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Annuler la demande</button>
                </div>
            </div>
            {{-- Historique --}}
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-card-dark border border-violet-100 dark:border-violet-900/20 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-900/20 dark:to-transparent border-b border-violet-100 dark:border-violet-900/20">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-500 text-base">history</span>
                        </div>
                        <h3 class="font-black text-base">Historique</h3>
                    </div>
                    <a href="#" class="text-[#6C2BD9] text-sm font-bold hover:underline">Voir tout</a>
                </div>
                <div class="divide-y divide-violet-50 dark:divide-white/5">
                    @foreach([
                        ['from'=>'Cotonou','to'=>'Porto-Novo','date'=>'03 Mar','price'=>'1 500','color'=>'bg-violet-100 dark:bg-violet-500/10 text-[#6C2BD9]'],
                        ['from'=>'Calavi','to'=>'Cotonou','date'=>'01 Mar','price'=>'800','color'=>'bg-blue-100 dark:bg-blue-500/10 text-blue-500'],
                        ['from'=>'Cotonou','to'=>'Ouidah','date'=>'28 Fév','price'=>'2 000','color'=>'bg-orange-100 dark:bg-orange-500/10 text-[#E8470A]'],
                        ['from'=>'Porto-Novo','to'=>'Cotonou','date'=>'25 Fév','price'=>'1 500','color'=>'bg-violet-100 dark:bg-violet-500/10 text-[#8B5CF6]'],
                    ] as $h)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-violet-50/50 dark:hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 rounded-lg {{ $h['color'] }} flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-base">route</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-xs">{{ $h['from'] }} → {{ $h['to'] }}</p>
                            <p class="text-xs text-slate-400">{{ $h['date'] }}</p>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex-shrink-0">-{{ $h['price'] }} FCFA</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
