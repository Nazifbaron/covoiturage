@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                      flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Demandes de courses</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Passagers qui cherchent un conducteur à proximité</p>
            </div>
        </div>
        {{-- Badge total --}}
        @if($pastrips->total() > 0)
        <div class="flex items-center gap-2 bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 px-4 py-2 rounded-xl">
            <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span>
            <span class="text-sm font-black text-violet-600 dark:text-violet-400">{{ $pastrips->total() }} demande{{ $pastrips->total() > 1 ? 's' : '' }}</span>
        </div>
        @endif
    </div>

    {{-- ── Filtres ── --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        @foreach([
            ['value' => '',           'label' => 'Toutes',    'icon' => 'list'],
            ['value' => 'today',      'label' => "Aujourd'hui",'icon' => 'today'],
            ['value' => 'tomorrow',   'label' => 'Demain',    'icon' => 'event'],
            ['value' => 'this_week',  'label' => 'Cette semaine', 'icon' => 'date_range'],
        ] as $filter)
        <a href="{{ request()->fullUrlWithQuery(['date_filter' => $filter['value'], 'page' => 1]) }}"
           class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold transition-all
                  {{ request('date_filter', '') === $filter['value']
                      ? 'bg-primary text-background-dark shadow-md shadow-primary/20'
                      : 'bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50' }}">
            <span class="material-symbols-outlined text-base">{{ $filter['icon'] }}</span>
            {{ $filter['label'] }}
        </a>
        @endforeach
    </div>

    {{-- ── Liste ── --}}
    @if($pastrips->isEmpty())
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-violet-400 text-3xl">hail</span>
            </div>
            <p class="font-black text-slate-700 dark:text-slate-300 text-lg">Aucune demande</p>
            <p class="text-sm text-slate-400 mt-1">Il n'y a pas encore de passagers qui cherchent un trajet.</p>
        </div>

    @else
        <div class="space-y-3">
            @foreach($pastrips as $trip)
            @php
                $isAccepted = $trip->status === 'accepted' && $trip->accepted_by === Auth::id();
                $isAcceptedByOther = $trip->status === 'accepted' && $trip->accepted_by !== Auth::id();
            @endphp

            <div class="bg-white dark:bg-card-dark rounded-2xl border
                        {{ $isAccepted ? 'border-emerald-200 dark:border-emerald-500/30' : 'border-slate-100 dark:border-primary/10' }}
                        shadow-sm overflow-hidden hover:shadow-md transition-all duration-200">

                {{-- Barre colorée --}}
                <div class="h-1 w-full {{ $isAccepted ? 'bg-emerald-400' : ($isAcceptedByOther ? 'bg-slate-300' : 'bg-violet-400') }}"></div>

                <div class="p-5">
                    <div class="flex items-start gap-4">

                        {{-- Avatar passager --}}
                        <div class="flex-shrink-0">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-purple-600
                                        flex items-center justify-center font-black text-white text-base shadow-md shadow-violet-200 dark:shadow-violet-900/30">
                                {{ strtoupper(substr($trip->user->first_name ?? $trip->user->name ?? '?', 0, 1)) }}
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-black text-slate-900 dark:text-white text-sm">
                                        {{ $trip->user->first_name ?? '' }} {{ $trip->user->last_name ?? $trip->user->name ?? '' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        {{-- Itinéraire inline --}}
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $trip->departure_city }}</span>
                                        <span class="material-symbols-outlined text-slate-300 dark:text-white/20" style="font-size:14px">arrow_forward</span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $trip->arrival_city }}</span>
                                    </div>
                                </div>

                                {{-- Badge statut --}}
                                @if($isAccepted)
                                <span class="flex-shrink-0 flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-xs font-black text-emerald-600 dark:text-emerald-400">
                                    <span class="material-symbols-outlined text-sm">check_circle</span> Acceptée
                                </span>
                                @elseif($isAcceptedByOther)
                                <span class="flex-shrink-0 flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-black text-slate-400">
                                    <span class="material-symbols-outlined text-sm">block</span> Prise
                                </span>
                                @else
                                <span class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 text-xs font-black text-violet-600 dark:text-violet-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span> En attente
                                </span>
                                @endif
                            </div>

                            {{-- Métadonnées --}}
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3">
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($trip->requested_date)->locale('fr')->isoFormat('ddd D MMM') }}
                                </span>
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">schedule</span>
                                    {{ \Carbon\Carbon::parse($trip->requested_time)->format('H\hi') }}
                                    @if($trip->flexibility > 0)
                                        <span class="text-slate-400">(±{{ $trip->flexibility >= 60 ? ($trip->flexibility/60).'h' : $trip->flexibility.'min' }})</span>
                                    @endif
                                </span>
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">group</span>
                                    {{ $trip->passengers }} passager{{ $trip->passengers > 1 ? 's' : '' }}
                                </span>
                                @if($trip->budget_max)
                                <span class="flex items-center gap-1 text-xs font-black text-emerald-600 dark:text-emerald-400">
                                    <span class="material-symbols-outlined" style="font-size:14px">payments</span>
                                    max {{ number_format($trip->budget_max, 0, ',', ' ') }} FCFA
                                </span>
                                @endif
                            </div>

                            {{-- Préférences --}}
                            @php
                                $prefs = [];
                                if($trip->need_luggage_space)  $prefs[] = ['icon' => 'luggage',        'label' => 'Bagages'];
                                if($trip->female_driver_only)  $prefs[] = ['icon' => 'female',         'label' => 'Conductrice'];
                                if($trip->pets_with_me)        $prefs[] = ['icon' => 'pets',           'label' => 'Animal'];
                                if($trip->silent_ride)         $prefs[] = ['icon' => 'volume_off',     'label' => 'Silence'];
                            @endphp
                            @if(count($prefs))
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @foreach($prefs as $pref)
                                <span class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    <span class="material-symbols-outlined" style="font-size:12px">{{ $pref['icon'] }}</span>
                                    {{ $pref['label'] }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            {{-- Adresses --}}
                            @if($trip->departure_address || $trip->arrival_address)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if($trip->departure_address)
                                <span class="text-xs text-slate-400 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-lg">📍 {{ $trip->departure_address }}</span>
                                @endif
                                @if($trip->arrival_address)
                                <span class="text-xs text-slate-400 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-lg">🏁 {{ $trip->arrival_address }}</span>
                                @endif
                            </div>
                            @endif

                            {{-- Message --}}
                            @if($trip->message)
                            <p class="mt-2 text-xs text-slate-400 italic line-clamp-2">"{{ $trip->message }}"</p>
                            @endif

                            {{-- Timer --}}
                            @if($trip->expires_at && $trip->status === 'pending')
                            <p class="mt-2 flex items-center gap-1 text-xs font-semibold
                                      {{ \Carbon\Carbon::parse($trip->expires_at)->diffInMinutes(now()) < 30 ? 'text-red-500' : 'text-orange-500' }}">
                                <span class="material-symbols-outlined" style="font-size:14px">timer</span>
                                @if(\Carbon\Carbon::parse($trip->expires_at)->isPast())
                                    Expirée
                                @else
                                    Expire {{ \Carbon\Carbon::parse($trip->expires_at)->diffForHumans() }}
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- ── Actions ── --}}
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-400">Publiée {{ $trip->created_at->diffForHumans() }}</span>

                        <div class="flex items-center gap-2">
                            @if($isAccepted)
                                {{-- Bouton chat --}}
                                <a href="{{ route('driver.chat', $trip->id) }}"
                                   class="flex items-center gap-2 px-4 py-2 rounded-xl
                                          bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black
                                          transition-all shadow-md shadow-emerald-200 dark:shadow-emerald-900/30">
                                    <span class="material-symbols-outlined text-base">chat</span>
                                    Contacter le passager
                                </a>
                            @elseif(!$isAcceptedByOther && $trip->status === 'pending')
                                {{-- Bouton accepter --}}
                                <form method="POST" action="{{ route('driver.accept', $trip->id) }}">
                                    @csrf
                                    @method('POST')
                                    <button type="submit"
                                            class="flex items-center gap-2 px-4 py-2 rounded-xl
                                                   bg-violet-500 hover:bg-violet-600 text-white text-xs font-black
                                                   transition-all shadow-md shadow-violet-200 dark:shadow-violet-900/30">
                                        <span class="material-symbols-outlined text-base">check_circle</span>
                                        Accepter la course
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($pastrips->hasPages())
        <div class="flex items-center justify-center gap-2 pb-6">
            @if($pastrips->onFirstPage())
                <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-300 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </span>
            @else
                <a href="{{ $pastrips->previousPageUrl() }}"
                   class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </a>
            @endif

            @foreach($pastrips->getUrlRange(1, $pastrips->lastPage()) as $page => $url)
                @if($page == $pastrips->currentPage())
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-primary text-background-dark font-black text-sm">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 font-semibold text-sm hover:border-primary/50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($pastrips->hasMorePages())
                <a href="{{ $pastrips->nextPageUrl() }}"
                   class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </a>
            @else
                <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-300 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </span>
            @endif
        </div>
        @endif
    @endif

</div>
@endsection
